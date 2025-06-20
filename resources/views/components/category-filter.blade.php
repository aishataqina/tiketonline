<div class="mb-6">

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('events.index') }}"
            class="px-4 py-2 rounded-full text-sm {{ !request('category') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Semua
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('events.index', ['category' => $category->id]) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('category') == $category->id ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                {{ $category->name }}
                <span class="text-xs ml-1">({{ $category->events_count }})</span>
            </a>
        @endforeach
    </div>
</div>
