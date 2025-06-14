@extends('layouts.user')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Order Details</h2>
                        <a href="{{ route('orders.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Orders
                        </a>
                    </div>

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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Event Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Event Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->event->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $order->event->event_date->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->event->location }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->quantity }} tickets</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Price</dt>
                                    <dd class="mt-1 text-lg font-bold text-blue-600">Rp
                                        {{ number_format($order->total_price, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if ($order->status === 'pending' && !$order->transaction)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-4">Please complete your payment to get your tickets.</p>
                                <form action="{{ route('transactions.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <div class="mb-4">
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                                            Method</label>
                                        <select name="payment_method" id="payment_method" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Select Payment Method</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="e_wallet">E-Wallet</option>
                                        </select>
                                        @error('payment_method')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Pay Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($order->transaction)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Transaction Code</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->transaction->transaction_code }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $order->transaction->payment_method)) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($order->transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->transaction->status === 'paid') bg-green-100 text-green-800
                                    @elseif($order->transaction->status === 'failed') bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->transaction->status) }}
                                        </span>
                                    </dd>
                                </div>
                                @if ($order->transaction->paid_at)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Paid At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $order->transaction->paid_at->format('d M Y H:i') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    @if ($order->status === 'paid')
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tickets</h3>
                            <div class="space-y-4">
                                @foreach ($order->tickets as $ticket)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Ticket Code:
                                                    {{ $ticket->ticket_code }}</p>
                                                <p class="text-sm text-gray-500">Status: {{ ucfirst($ticket->status) }}</p>
                                            </div>
                                            <button onclick="window.print()"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Print Ticket
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
