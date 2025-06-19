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

        // Cek apakah event sudah ada di keranjang
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        // Jika event sudah ada di keranjang, tambahkan quantity nya
        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $request->quantity;

            // Cek apakah total quantity tidak melebihi kuota event
            if ($newQuantity > $event->remaining_quota) {
                return redirect()->back()->with('error', 'Total tiket melebihi kuota yang tersedia.');
            }

            $existingCart->update([
                'quantity' => $newQuantity,
                'expired_at' => now()->addMinutes(15)
            ]);

            return redirect()->route('cart.index')->with('success', 'Jumlah tiket berhasil diperbarui di keranjang.');
        }

        // Jika event belum ada di keranjang, buat item baru
        if ($request->quantity > $event->remaining_quota) {
            return redirect()->back()->with('error', 'Jumlah tiket melebihi kuota yang tersedia.');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'quantity' => $request->quantity,
            'price' => $event->price,
            'expired_at' => now()->addMinutes(15)
        ]);

        return redirect()->route('cart.index')->with('success', 'Tiket berhasil ditambahkan ke keranjang.');
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
        $transactionIds = [];
        foreach ($carts as $cart) {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'event_id' => $cart->event_id,
                'quantity' => $cart->quantity,
                'amount' => $cart->price * $cart->quantity,
                'status' => 'pending',
            ]);
            $transactionIds[] = $transaction->id;
        }
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat untuk setiap event, silakan lakukan pembayaran.');
    }
}
