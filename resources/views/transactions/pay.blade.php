@extends('layouts.user')
@section('title', 'Pembayaran Pesanan')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Pembayaran Pesanan #{{ $order->id }}</h2>
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}</div>
                    @endif
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Event</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->event->title ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                        {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($order->status) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <form id="midtrans-form" method="POST" action="#" onsubmit="return false;">
                        <button id="pay-button" type="button" class="bg-blue-600 text-white px-4 py-2 rounded">Bayar dengan
                            Midtrans</button>
                    </form>
                    <div class="mt-4">
                        <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:underline">Kembali ke Daftar
                            Pembayaran</a>
                    </div>
                    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
                    </script>
                    <script>
                        document.getElementById('pay-button').onclick = function() {
                            window.snap.pay('{{ $snapToken }}', {
                                onSuccess: function(result) {
                                    window.location.href = "{{ route('transactions.index') }}?paid=1";
                                },
                                onPending: function(result) {
                                    alert('Transaksi belum selesai, silakan selesaikan pembayaran.');
                                },
                                onError: function(result) {
                                    alert('Pembayaran gagal atau dibatalkan.');
                                },
                                onClose: function() {
                                    alert('Kamu menutup popup tanpa menyelesaikan pembayaran');
                                }
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
