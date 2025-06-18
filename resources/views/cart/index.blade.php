@extends('layouts.user')
@section('title', 'Keranjang Tiket')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Keranjang Tiket</h2>
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}</div>
                    @endif
                    @if ($carts->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-700">Keranjang kosong.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Event</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Jumlah Tiket</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Harga</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Subtotal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($carts as $cart)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $cart->event->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <form method="POST" action="{{ route('cart.update', $cart) }}"
                                                    class="flex items-center">
                                                    @csrf @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $cart->quantity }}"
                                                        min="1" class="w-16 border rounded">
                                                    <button type="submit" class="ml-2 text-blue-600">Update</button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                                {{ number_format($cart->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                                {{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <form method="POST" action="{{ route('cart.destroy', $cart) }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-between items-center mt-4">
                            <div>
                                <span class="font-bold">Total: </span>
                                Rp {{ number_format($carts->sum(fn($c) => $c->price * $c->quantity), 0, ',', '.') }}
                            </div>
                            <form method="POST" action="{{ route('cart.checkout') }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lanjutkan ke
                                    Pembayaran</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
