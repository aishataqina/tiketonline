<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generateSalesReport(Request $request)
    {
        $orders = Order::with(['event', 'user'])
            ->when($request->periode === 'harian', function ($query) {
                return $query->whereDate('created_at', now());
            })
            ->when($request->periode === 'mingguan', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->when($request->periode === 'bulanan', function ($query) {
                return $query->whereMonth('created_at', now()->month);
            })
            ->where('status', 'paid')
            ->get();

        $data = [
            'title' => 'Laporan Penjualan',
            'date' => date('d/m/Y'),
            'orders' => $orders,
            'total' => $orders->sum('total_price')
        ];

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('admin.reports.sales_pdf', $data);
            return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
        }

        // Excel format akan diimplementasikan nanti
        return back()->with('error', 'Format Excel belum tersedia');
    }

    public function generateFinanceReport(Request $request)
    {
        DB::enableQueryLog();

        $today = Carbon::now()->format('Y-m-d');

        $transactions = Transaction::with(['event', 'user'])
            ->when($request->periode === 'harian', function ($query) use ($today) {
                return $query->whereRaw('DATE(paid_at) = ?', [$today]);
            })
            ->when($request->periode === 'mingguan', function ($query) {
                return $query->whereBetween('paid_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
            })
            ->when($request->periode === 'bulanan', function ($query) {
                return $query->whereBetween('paid_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]);
            })
            ->where('status', 'success')
            ->orderBy('paid_at', 'desc')
            ->get();

        Log::info('Query Log:', DB::getQueryLog());
        Log::info('Transactions Count: ' . $transactions->count());
        Log::info('Today: ' . $today);
        Log::info('Transactions:', $transactions->toArray());

        $data = [
            'title' => 'Laporan Keuangan',
            'date' => date('d/m/Y'),
            'transactions' => $transactions,
            'total' => $transactions->sum('amount')
        ];

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('admin.reports.finance_pdf', $data);
            return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
        }

        return back()->with('error', 'Format Excel belum tersedia');
    }

    public function generateEventsReport(Request $request)
    {
        $events = Event::withCount(['orders' => function ($query) {
            $query->where('status', 'paid');
        }])
            ->with('category')
            ->orderBy('status', 'asc')
            ->orderBy('event_date', 'asc')
            ->get();

        $data = [
            'title' => 'Laporan Event',
            'date' => date('d/m/Y'),
            'events' => $events
        ];

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('admin.reports.events_pdf', $data);
            return $pdf->download('laporan-event-' . date('Y-m-d') . '.pdf');
        }

        return back()->with('error', 'Format Excel belum tersedia');
    }

    public function generateCustomersReport(Request $request)
    {
        $customers = User::where('is_admin', false)
            ->with('customer')
            ->withCount(['orders' => function ($query) {
                $query->where('status', 'paid');
            }])
            ->orderBy('name')
            ->get();

        $data = [
            'title' => 'Laporan Customer',
            'date' => date('d/m/Y'),
            'customers' => $customers
        ];

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('admin.reports.customers_pdf', $data);
            return $pdf->download('laporan-customer-' . date('Y-m-d') . '.pdf');
        }

        return back()->with('error', 'Format Excel belum tersedia');
    }
}
