@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Statistik -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary text-white rounded-3 p-3 me-3">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Event</h6>
                            <h2 class="card-title mb-0">{{ $totalEvents }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success text-white rounded-3 p-3 me-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Event Aktif</h6>
                            <h2 class="card-title mb-0">{{ $activeEvents }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning text-white rounded-3 p-3 me-3">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Pesanan</h6>
                            <h2 class="card-title mb-0">{{ $totalOrders }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info text-white rounded-3 p-3 me-3">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Pendapatan</h6>
                            <h2 class="card-title mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Event Terbaru -->
            <div class="col-12 col-xl-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Event Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Tanggal</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestEvents as $event)
                                        <tr>
                                            <td>{{ $event->title }}</td>
                                            <td>{{ $event->event_date->format('d M Y H:i') }}</td>
                                            <td>{{ $event->location }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $event->status === 'active' ? 'bg-success' : ($event->status === 'inactive' ? 'bg-secondary' : 'bg-danger') }}">
                                                    {{ ucfirst($event->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Terbaru -->
            <div class="col-12 col-xl-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pesanan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Pembeli</th>
                                        <th>Event</th>
                                        <th>Tanggal Order</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestOrders as $order)
                                        <tr>
                                            <td>
                                                <div>{{ $order->user->name }}</div>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </td>
                                            <td>{{ $order->event->title }}</td>
                                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $order->status === 'completed'
                                                        ? 'bg-success'
                                                        : ($order->status === 'pending'
                                                            ? 'bg-warning'
                                                            : ($order->status === 'cancelled'
                                                                ? 'bg-danger'
                                                                : 'bg-secondary')) }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
