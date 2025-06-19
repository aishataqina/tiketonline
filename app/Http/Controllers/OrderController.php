<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        if (Auth::user() && Auth::user()->is_admin) {
            abort(403, 'Admin tidak boleh mengakses halaman user.');
        }

        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->with(['event', 'tickets'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user() && Auth::user()->is_admin) {
            abort(403, 'Admin tidak boleh melakukan order.');
        }
        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $event = Event::findOrFail($request->event_id);

        if ($event->status !== 'active' || $event->remaining_quota < $request->quantity) {
            return back()->with('error', 'Tiket tidak tersedia.');
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'quantity' => $request->quantity,
                'total_price' => $event->price * $request->quantity,
                'status' => 'pending',
                'order_code' => 'ORD-' . Str::upper(Str::random(10)), // <<< Tambahkan baris ini
            ]);


            $event->decrement('remaining_quota', $request->quantity);

            if ($event->remaining_quota === 0) {
                $event->update(['status' => 'sold_out']);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        if (Auth::user() && Auth::user()->is_admin) {
            abort(403, 'Admin tidak boleh mengakses halaman user.');
        }
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load(['event']);
        return view('orders.show', compact('order'));
    }
}
