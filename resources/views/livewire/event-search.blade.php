<div>
    <div class="mb-4 flex items-center justify-between gap-3">
        <div class=" w-full ">
            <input wire:model.live="search" type="text"
                class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500"
                placeholder="Cari event...">
        </div>
        <select wire:model.live="status" class="rounded-lg border px-10 focus:outline-none focus:border-blue-500">
            <option value="">Semua Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('id')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        no
                        @if ($sortField === 'id')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('title')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Nama Event
                        @if ($sortField === 'title')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('event_date')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Tanggal & Waktu
                        @if ($sortField === 'event_date')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('location')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Lokasi
                        @if ($sortField === 'location')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('price')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Harga
                        @if ($sortField === 'price')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('remaining_quota')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Kuota
                        @if ($sortField === 'remaining_quota')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Status
                        @if ($sortField === 'status')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($events as $event)
                    <tr>
                        <td class="px-6 py-4">{{ $event->id }}</td>
                        <td class="px-6 py-4">{{ $event->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $event->event_date->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">{{ $event->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $event->remaining_quota }}/{{ $event->quota }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $event->status === 'active'
                                    ? 'bg-green-100 text-green-800'
                                    : ($event->status === 'inactive'
                                        ? 'bg-gray-100 text-gray-700'
                                        : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.events.show', $event) }}"
                                class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                            <a href="{{ route('admin.events.edit', $event) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>
