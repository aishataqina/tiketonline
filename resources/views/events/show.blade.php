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

                                @if ($event->status === 'active' && $event->remaining_quota > 0)
                                    <form action="{{ route('orders.store') }}" method="POST" class="mt-6">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <div class="mb-4">
                                            <label for="quantity" class="block text-sm font-medium text-gray-700">Number of
                                                Tickets</label>
                                            <input type="number" name="quantity" id="quantity" min="1"
                                                max="{{ $event->remaining_quota }}" value="1" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('quantity')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit"
                                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Buy Tickets
                                        </button>
                                    </form>
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
