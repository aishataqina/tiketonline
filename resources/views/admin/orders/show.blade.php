@extends('layouts.admin')

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Kembali ke Pesanan
    </a>
    <div class="bg-white overflow-hidden mt-6 shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Detail Pesanan</h2>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Information</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Order ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Status</dt>
                            <dd class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status === 'paid'
                                    ? 'bg-green-100 text-green-800'
                                    : ($order->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->user->email }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Event</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Nama Event</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->event->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Event Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->event->event_date->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->event->location }}</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Quantity</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->quantity }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Price per Ticket</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->event->price, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-700">Total Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Hapus/komentari bagian berikut karena relasi transaction sudah tidak ada --}}
            {{--
            @if ($order->transaction)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Transaction Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->transaction->transaction_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $order->transaction->payment_method)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700">Payment Status</dt>
                            <dd class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $order->transaction->status === 'success'
                                ? 'bg-green-100 text-green-800'
                                : ($order->transaction->status === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($order->transaction->status) }}
                                </span>
                            </dd>
                        </div>
                        @if ($order->transaction->paid_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Paid At</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $order->transaction->paid_at->format('d M Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif
            --}}
        </div>
    </div>
@endsection
