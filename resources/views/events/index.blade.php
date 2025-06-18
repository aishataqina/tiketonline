@extends('layouts.user')

@section('content')
    <div class="py-10">
        <div class="max-w-7xl mx-auto">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Event & Tiket Tersedia</h2>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($events->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-700">No upcoming events available.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach ($events as $event)
                            @include('components.event-card', ['event' => $event])
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
