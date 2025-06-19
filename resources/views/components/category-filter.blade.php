<div class="bg-white p-4 rounded-lg shadow mb-6">
    <h3 class="text-lg font-semibold mb-4">Kategori Event</h3>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('events.index') }}"
            class="px-4 py-2 rounded-full text-sm {{ !request('category') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Semua
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('events.index', ['category' => $category->id]) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('category') == $category->id ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $category->name }}
                <span class="text-xs ml-1">({{ $category->events_count }})</span>
            </a>
        @endforeach
    </div>
</div>
