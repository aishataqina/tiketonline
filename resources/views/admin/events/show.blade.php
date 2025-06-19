@extends('layouts.admin')

@section('content')
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="relative mb-6">
                <div class="absolute left-0">
                    <a href="{{ route('admin.events.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali ke Daftar Event
                    </a>
                </div>
                <h2 class="text-2xl font-semibold text-center">Event Details</h2>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex gap-8">
                        <div class="w-3/12">
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                                    class="w-full h-auto object-cover rounded-lg">
                            @endif
                        </div>

                        <div class="w-9/12">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Informasi Event</h3>
                                    <dl class="mt-2 space-y-2">
                                        <div>
                                            <div class="flex gap-2 items-center ">
                                                <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $event->title }}
                                                </dd>
                                                <dd class="mt-1">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($event->status === 'active') bg-green-100 text-green-800
                                                @elseif($event->status === 'sold_out') bg-red-100 text-red-800
                                                @elseif($event->status === 'cancelled') bg-gray-100 text-gray-700 @endif">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                </dd>
                                            </div>

                                            <dd class="mt-1 text-sm text-gray-900">{{ $event->description }}</dd>
                                        </div>
                                        <div class="">
                                            <div class="flex gap-1 items-center">
                                                <i class="fa-solid fa-money-bill me-1" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-700">Price:</dt>
                                            </div>
                                            <dd class="text-xl font-semibold text-blue-500">Rp
                                                {{ number_format($event->price, 0, ',', '.') }}</dd>
                                        </div>
                                        <div class="">
                                            <div class="flex gap-1 items-center">
                                                <i class="fas fa-calendar-alt me-1" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-700">Tanggal & Waktu:</dt>
                                            </div>
                                            <dd class="text-sm text-gray-900">
                                                {{ $event->event_date->format('d M Y H:i') }}</dd>
                                        </div>

                                        <div class="">
                                            <div class="flex gap-1 items-center">
                                                <i class="fa-solid fa-location-dot me-1" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-700">Location:</dt>
                                            </div>
                                            <dd class="text-sm text-gray-900">{{ $event->location }}</dd>
                                        </div>

                                        <div class="">
                                            <div class="flex gap-1 items-center">
                                                <i class="fa-solid fa-tag me-1" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-700">Kategori:</dt>
                                            </div>
                                            <dd class="text-sm text-gray-900">
                                                @if ($event->category)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $event->category->name }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Tidak ada kategori
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>

                                        <div class="">
                                            <dt class="text-sm font-medium text-gray-700">Quota:</dt>
                                            <dd class="text-sm text-gray-900">{{ $event->quota }} tickets</dd>
                                        </div>
                                        <div class="">
                                            <dt class="text-sm font-medium text-gray-700">Remaining Quota:</dt>
                                            <dd class="text-sm text-gray-900">{{ $event->remaining_quota }} tickets
                                            </dd>
                                        </div>
                                        <div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pesanan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Order ID</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            User</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Quantity</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Total Price</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($event->orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                                {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-blue-600 hover:text-blue-900">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                                                No orders found for this event.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
