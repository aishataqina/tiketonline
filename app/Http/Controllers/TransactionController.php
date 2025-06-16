<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * Display a listing of the user's transactions.
     */
    public function index()
    {
        $transactions = Transaction::whereHas('order', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('order')->latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }
}
