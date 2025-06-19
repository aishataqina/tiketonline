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
use App\Models\Event;
use App\Models\Ticket;

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

        if ($order->user_id !== Auth::id()) {
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
            // Generate order ID yang konsisten
            $orderId = 'ORDER-' . $transaction->id . '-' . Str::random(8);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$transaction->amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $transaction->snap_token = $snapToken;
                $transaction->midtrans_order_id = $orderId; // Simpan order ID
                $transaction->save();
            } catch (\Exception $e) {
                \Log::error('Error generating snap token:', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
                return back()->with('error', 'Terjadi kesalahan saat mempersiapkan pembayaran. Silakan coba lagi.');
            }
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
            // Buat order baru dari transaksi jika belum ada
            $existingOrder = Order::where('user_id', $transaction->user_id)
                ->where('event_id', $transaction->event_id)
                ->where('quantity', $transaction->quantity)
                ->where('total_price', $transaction->amount)
                ->where('status', 'paid')
                ->first();

            if (!$existingOrder) {
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

                // Generate tiket sesuai quantity
                for ($i = 0; $i < $transaction->quantity; $i++) {
                    \App\Models\Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $order->event_id,
                        'user_id' => $order->user_id,
                        'ticket_code' => 'TIKET-' . strtoupper(uniqid()),
                        'status' => 'sold',
                    ]);
                }
            }

            $transaction->status = 'success';
            $transaction->paid_at = now();
            $transaction->payment_type = $notif->payment_type;
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

        try {
            Log::info('Mulai cek pembayaran:', [
                'transaction_id' => $transaction->id,
                'current_status' => $transaction->status,
                'midtrans_order_id' => $transaction->midtrans_order_id
            ]);

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            if (!$transaction->midtrans_order_id) {
                throw new \Exception('Order ID Midtrans tidak ditemukan');
            }

            Log::info('Cek status Midtrans:', ['order_id' => $transaction->midtrans_order_id]);

            /** @var object $status */
            $status = \Midtrans\Transaction::status($transaction->midtrans_order_id);
            Log::info('Status dari Midtrans:', ['status' => $status]);

            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                if ($transaction->status !== 'success') {
                    Log::info('Pembayaran berhasil, memproses order');

                    $existingOrder = Order::where('user_id', $transaction->user_id)
                        ->where('event_id', $transaction->event_id)
                        ->where('quantity', $transaction->quantity)
                        ->where('total_price', $transaction->amount)
                        ->where('status', 'paid')
                        ->first();

                    if (!$existingOrder) {
                        $order = Order::create([
                            'user_id' => $transaction->user_id,
                            'event_id' => $transaction->event_id,
                            'quantity' => $transaction->quantity,
                            'total_price' => $transaction->amount,
                            'status' => 'paid',
                        ]);

                        $event = Event::find($transaction->event_id);
                        if ($event) {
                            $event->decrement('remaining_quota', $transaction->quantity);
                            if ($event->remaining_quota <= 0) {
                                $event->update(['status' => 'sold_out']);
                            }
                        }

                        for ($i = 0; $i < $transaction->quantity; $i++) {
                            Ticket::create([
                                'order_id' => $order->id,
                                'event_id' => $order->event_id,
                                'user_id' => $order->user_id,
                                'ticket_code' => 'TIKET-' . strtoupper(uniqid()),
                                'status' => 'sold',
                            ]);
                        }
                    }

                    $transaction->status = 'success';
                    $transaction->paid_at = now();
                    $transaction->payment_type = $status->payment_type ?? null;
                    $transaction->save();

                    Log::info('Pembayaran berhasil diproses:', [
                        'transaction_id' => $transaction->id,
                        'new_status' => $transaction->status
                    ]);

                    return response()->json(['success' => true]);
                }
            }

            Log::warning('Pembayaran belum selesai:', [
                'transaction_id' => $transaction->id,
                'midtrans_status' => $status->transaction_status ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status pembayaran: ' . ucfirst($status->transaction_status ?? 'unknown')
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat konfirmasi pembayaran:', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengecek status pembayaran: ' . $e->getMessage()
            ]);
        }
    }
}
