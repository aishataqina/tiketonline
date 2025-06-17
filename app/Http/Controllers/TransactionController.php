<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'payment_method' => ['required', 'in:bank_transfer,e_wallet']
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending' || $order->transaction) {
            return back()->with('error', 'Order tidak dapat diproses.');
        }

        $transaction = Transaction::create([
            'order_id' => $order->id,
            'transaction_code' => 'TRX-' . Str::random(10),
            'amount' => $order->total_price,
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Transaksi berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Show the payment page for a specific order.
     */
    public function pay(Transaction $transaction)
    {
        $transaction->load('event');
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        if (!in_array($transaction->status, ['pending', 'unpaid'])) {
            return redirect()->route('transactions.index')->with('error', 'Transaksi sudah dibayar atau tidak valid.');
        }

        // Midtrans config
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Snap Token hanya digenerate sekali per transaksi
        if (empty($transaction->snap_token)) {
            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $transaction->id . '-' . now()->timestamp,
                    'gross_amount' => $transaction->amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;
            $transaction->save();
        } else {
            $snapToken = $transaction->snap_token;
        }

        return view('transactions.pay', compact('transaction', 'snapToken'));
    }

    /**
     * Display a listing of the user's unpaid orders (transactions).
     */
    public function index()
    {
        // Untuk menu Pembayaran: ambil transaksi pending
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function midtransCallback(Request $request)
    {
        dd($request->all());
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $notif = new \Midtrans\Notification();

        \Log::info('Midtrans Callback:', [
            'order_id' => $notif->order_id,
            'transaction_status' => $notif->transaction_status,
            'payment_type' => $notif->payment_type,
            'fraud_status' => $notif->fraud_status,
            'gross_amount' => $notif->gross_amount
        ]);

        preg_match('/ORDER-(\d+)/', $notif->order_id, $matches);
        $transactionId = $matches[1] ?? null;
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            \Log::error('Transaction not found:', ['transaction_id' => $transactionId]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($notif->transaction_status == 'settlement' || $notif->transaction_status == 'capture') {
            // Buat order baru dari transaksi
            $order = Order::create([
                'user_id' => $transaction->user_id,
                'event_id' => $transaction->event_id,
                'quantity' => $transaction->quantity,
                'total_price' => $transaction->amount,
                'status' => 'paid',
            ]);
            // Update sisa kuota event
            $event = \App\Models\Event::find($transaction->event_id);
            if ($event) {
                $event->decrement('remaining_quota', $transaction->quantity);
                if ($event->remaining_quota <= 0) {
                    $event->update(['status' => 'sold_out']);
                }
            }
            $transaction->status = 'success';
            $transaction->paid_at = now();
            $transaction->save();
        } elseif ($notif->transaction_status == 'pending') {
            $transaction->status = 'pending';
            $transaction->save();
        } elseif ($notif->transaction_status == 'deny') {
            $transaction->status = 'denied';
            $transaction->save();
        } elseif ($notif->transaction_status == 'expire') {
            $transaction->status = 'expired';
            $transaction->save();
        } elseif ($notif->transaction_status == 'cancel') {
            $transaction->status = 'cancelled';
            $transaction->save();
        }

        \Log::info('Transaction updated:', ['transaction_id' => $transaction->id, 'new_status' => $transaction->status]);
        return response()->json(['success' => true]);
    }

    /**
     * Create a new transaction from event_id and quantity, then redirect to payment page.
     */
    public function create(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $event = \App\Models\Event::findOrFail($request->event_id);
        $user = Auth::user();

        // Cek kuota
        if ($event->remaining_quota < $request->quantity) {
            return redirect()->back()->with('error', 'Sisa kuota tidak mencukupi.');
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => $request->quantity,
            'amount' => $event->price * $request->quantity,
            'status' => 'pending',
        ]);

        return redirect()->route('transactions.pay', $transaction)->with('success', 'Transaksi berhasil dibuat, silakan lakukan pembayaran.');
    }

    /**
     * Konfirmasi pembayaran manual (tanpa callback), update status dan quota jika sudah settlement/capture.
     */
    public function confirmPayment($id)
    {
        $transaction = Transaction::findOrFail($id);
        // Cek status ke Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        $status = \Midtrans\Transaction::status('ORDER-' . $transaction->id);
        if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
            if ($transaction->status !== 'success') {
                $transaction->status = 'success';
                $transaction->paid_at = now();
                $transaction->save();
                $event = \App\Models\Event::find($transaction->event_id);
                if ($event) {
                    $event->decrement('remaining_quota', $transaction->quantity);
                    if ($event->remaining_quota <= 0) {
                        $event->update(['status' => 'sold_out']);
                    }
                }
                // Buat order baru dari transaksi jika belum ada order untuk user, event, dan quantity yang sama
                $existingOrder = \App\Models\Order::where('user_id', $transaction->user_id)
                    ->where('event_id', $transaction->event_id)
                    ->where('quantity', $transaction->quantity)
                    ->where('total_price', $transaction->amount)
                    ->where('status', 'paid')
                    ->first();
                if (!$existingOrder) {
                    \App\Models\Order::create([
                        'user_id' => $transaction->user_id,
                        'event_id' => $transaction->event_id,
                        'quantity' => $transaction->quantity,
                        'total_price' => $transaction->amount,
                        'status' => 'paid',
                    ]);
                }
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
