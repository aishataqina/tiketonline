@extends('layouts.user')
@section('title', 'Pembayaran Pesanan')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Pembayaran Pesanan</h2>
                        <a href="{{ route('transactions.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali ke Daftar Pembayaran
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
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Event</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Judul Event</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transaction->event->title ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $transaction->event->event_date ? $transaction->event->event_date->format('d M Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transaction->event->location ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Transaksi</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Transaksi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transaction->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Tiket</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transaction->quantity }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Harga</dt>
                                    <dd class="mt-1 text-lg font-bold text-blue-600">Rp
                                        {{ number_format($transaction->amount, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status === 'success') bg-green-100 text-green-800
                                            @elseif($transaction->status === 'failed') bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if ($transaction->status === 'pending')
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pembayaran</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-4">Silakan klik tombol di bawah untuk melakukan
                                    pembayaran melalui Midtrans.</p>
                                <form id="midtrans-form" method="POST" action="#" onsubmit="return false;">
                                    <button id="pay-button" type="button"
                                        class="bg-blue-600 text-white px-4 py-2 rounded">Bayar dengan Midtrans</button>
                                </form>
                            </div>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
