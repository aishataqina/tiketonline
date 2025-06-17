<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Event;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('event')->get();
        return view('cart.index', compact('carts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $event = Event::findOrFail($request->event_id);
        $cart = Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'event_id' => $event->id],
            ['quantity' => $request->quantity, 'price' => $event->price, 'expired_at' => now()->addMinutes(15)]
        );
        return redirect()->route('cart.index')->with('success', 'Tiket ditambahkan ke keranjang.');
    }
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengubah item ini.');
        }
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Jumlah tiket diperbarui.');
    }
    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menghapus item ini.');
        }
        $cart->delete();
        return back()->with('success', 'Tiket dihapus dari keranjang.');
    }
    public function checkout(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->with('event')->get();
        if ($carts->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }
        $total = $carts->sum(fn($c) => $c->price * $c->quantity);
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'event_id' => $carts->first()->event_id,
            'quantity' => $carts->sum('quantity'),
            'amount' => $total,
            'status' => 'pending',
        ]);
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat, silakan lakukan pembayaran.');
    }
}
