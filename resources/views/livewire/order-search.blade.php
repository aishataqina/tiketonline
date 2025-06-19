<div>
    <div class="mb-4 flex items-center justify-between gap-3">
        <div class="w-full">
            <input wire:model.live="search" type="text"
                class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500"
                placeholder="Cari pesanan...">
        </div>
        <select wire:model.live="status" class="rounded-lg border px-10 focus:outline-none focus:border-blue-500">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
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
                    <th wire:click="sortBy('order_code')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        id pesanan
                        @if ($sortField === 'order_code')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                        Nama Pelanggan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                        Event
                    </th>
                    <th wire:click="sortBy('quantity')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Jumlah Tiket
                        @if ($sortField === 'quantity')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('total_price')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Total Harga
                        @if ($sortField === 'total_price')
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
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-6 py-4">{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->event->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status === 'paid'
                                    ? 'bg-green-100 text-green-800'
                                    : ($order->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="text-indigo-600 hover:text-indigo-900">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
