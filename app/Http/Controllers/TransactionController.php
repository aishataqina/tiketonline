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
    public function pay(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        if (!in_array($order->status, ['pending', 'unpaid'])) {
            return redirect()->route('orders.index')->with('error', 'Pesanan sudah dibayar atau tidak valid.');
        }

        // Midtrans config
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Snap Token hanya digenerate sekali per order
        if (empty($order->snap_token)) {
            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . now()->timestamp,
                    'gross_amount' => $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snap_token = $snapToken;
            $order->save();
        } else {
            $snapToken = $order->snap_token;
        }

        return view('transactions.pay', compact('order', 'snapToken'));
    }

    /**
     * Display a listing of the user's unpaid orders (transactions).
     */
    public function index()
    {
        // Untuk menu Pembayaran
        $orders = Auth::user()->orders()
            ->whereIn('status', ['pending', 'unpaid'])
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('orders'));
    }

    public function midtransCallback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $notif = new \Midtrans\Notification();

        // Log untuk debugging
        \Log::info('Midtrans Callback:', [
            'order_id' => $notif->order_id,
            'transaction_status' => $notif->transaction_status,
            'payment_type' => $notif->payment_type,
            'fraud_status' => $notif->fraud_status,
            'gross_amount' => $notif->gross_amount
        ]);

        $orderId = str_replace('ORDER-', '', $notif->order_id);
        $order = \App\Models\Order::find($orderId);

        if (!$order) {
            \Log::error('Order not found:', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status berdasarkan notifikasi
        if ($notif->transaction_status == 'capture') {
            if ($notif->payment_type == 'credit_card') {
                if ($notif->fraud_status == 'challenge') {
                    $order->status = 'challenge';
                } else {
                    $order->status = 'paid';
                }
            }
        } else if ($notif->transaction_status == 'settlement') {
            $order->status = 'paid';
        } else if ($notif->transaction_status == 'pending') {
            $order->status = 'pending';
        } else if ($notif->transaction_status == 'deny') {
            $order->status = 'denied';
        } else if ($notif->transaction_status == 'expire') {
            $order->status = 'expired';
        } else if ($notif->transaction_status == 'cancel') {
            $order->status = 'cancelled';
        }

        $order->save();
        \Log::info('Order updated:', ['order_id' => $order->id, 'new_status' => $order->status]);

        return response()->json(['success' => true]);
    }
}
