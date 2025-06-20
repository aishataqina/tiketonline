<div class="bg-white rounded-lg shadow-md overflow-hidden relative">
    @if ($event->remaining_quota <= 0)
        <div class="absolute inset-0 bg-gray-800/60 flex items-center justify-center z-10">
            <span class="text-white text-2xl font-bold px-6 py-2 rounded-lg">HABIS</span>
        </div>
    @endif
    @if ($event->image)
        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
    @endif
    <div class="p-3">
        <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $event->title }}</h3>
        <div class="flex items-center text-xl font-bold text-blue-600 mb-2">
            Rp {{ number_format($event->price, 0, ',', '.') }}
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm text-gray-700 gap-2">
                <i class="fa-solid fa-calendar" style="color: #374151;"></i>
                {{ $event->event_date->format('d M Y H:i') }}
            </div>
            <div class="flex items-center text-sm text-gray-700 gap-2">
                <i class="fa-solid fa-location-dot" style="color: #374151;"></i>
                <p class="line-clamp-1">{{ $event->location }}</p>
            </div>
            <div class="flex items-center text-sm text-gray-700 gap-2">
                <i class="fa-solid fa-ticket" style="color: #374151;"></i>
                @if ($event->remaining_quota <= 0)
                    <span class="text-red-500 font-medium">Tiket Habis</span>
                @else
                    {{ $event->remaining_quota }} tiket tersisa
                @endif
            </div>
        </div>
        <a href="{{ route('events.show', $event) }}"
            class="block w-full {{ $event->remaining_quota <= 0 ? 'bg-gray-500 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-700' }} text-white font-bold py-1 px-4 rounded text-center">
            Lihat Detail
        </a>
    </div>
</div>
