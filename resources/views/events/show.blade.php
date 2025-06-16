@extends('layouts.user')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('events.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Events
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="w-full md:w-4/12">
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover rounded-lg">
                            @endif
                        </div>

                        <div class="w-full md:w-8/12">
                            <div class="space-y-4">
                                <div>
                                    <h2 class="text-2xl font-semibold">{{ $event->title }}</h2>
                                    <p class="mt-2 text-gray-600">{{ $event->description }}</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Event Details</h3>
                                    <dl class="mt-2 space-y-2">
                                        <div>
                                            <div class="flex gap-2 items-center">
                                                <i class="fa-solid fa-calendar-alt" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                                            </div>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ $event->event_date->format('d M Y H:i') }}</dd>
                                        </div>
                                        <div>
                                            <div class="flex gap-2 items-center">
                                                <i class="fa-solid fa-location-dot" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                            </div>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $event->location }}</dd>
                                        </div>
                                        <div>
                                            <div class="flex gap-2 items-center">
                                                <i class="fa-solid fa-ticket" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-500">Available Tickets</dt>
                                            </div>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $event->remaining_quota }} of
                                                {{ $event->quota }}</dd>
                                        </div>
                                        <div>
                                            <div class="flex gap-2 items-center">
                                                <i class="fa-solid fa-money-bill" style="color: #a0aaba;"></i>
                                                <dt class="text-sm font-medium text-gray-500">Price</dt>
                                            </div>
                                            <dd class="mt-1 text-lg font-bold text-blue-600">Rp
                                                {{ number_format($event->price, 0, ',', '.') }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <p class="mb-2"><strong>Sisa Kuota:</strong> {{ $event->remaining_quota }} dari
                                    {{ $event->quota }}</p>
                                @if ($event->status === 'active' && $event->remaining_quota > 0)
                                    <form action="{{ route('cart.store') }}" method="POST"
                                        class="mt-4 flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <label for="quantity" class="mr-2">Jumlah:</label>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1"
                                            max="{{ $event->remaining_quota }}" class="w-20 border rounded px-2 py-1">
                                        <button type="submit"
                                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                    <form action="{{ route('orders.store') }}" method="POST"
                                        class="mt-2 flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="quantity" id="buy_quantity" value="1">
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                            Beli Tiket
                                        </button>
                                    </form>
                                    <script>
                                        // Sinkronkan jumlah di kedua form
                                        document.getElementById('quantity').addEventListener('input', function() {
                                            document.getElementById('buy_quantity').value = this.value;
                                        });
                                    </script>
                                @else
                                    <div class="mt-6">
                                        <button disabled
                                            class="w-full bg-gray-500 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                            {{ $event->status === 'sold_out' ? 'Sold Out' : 'Not Available' }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
