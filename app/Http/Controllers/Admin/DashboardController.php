<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::count();
        $activeEvents = Event::where('status', 'active')->count();
        $totalOrders = Order::count();
        $totalRevenue = Transaction::where('status', 'success')->sum('amount');

        $latestEvents = Event::latest()->take(5)->get();
        $latestOrders = Order::with(['user', 'event'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalEvents',
            'activeEvents',
            'totalOrders',
            'totalRevenue',
            'latestEvents',
            'latestOrders'
        ));
    }
}
