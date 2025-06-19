<div>
    <div class="mb-4 flex items-center justify-between gap-3">
        <div class=" w-full ">
            <input wire:model.live="search" type="text"
                class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500"
                placeholder="Cari transaksi...">
        </div>
        <select wire:model.live="status" class="rounded-lg border px-10 focus:outline-none focus:border-blue-500">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="success">Success</option>
            <option value="failed">Failed</option>
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
                    <th wire:click="sortBy('transaction_code')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Kode Transaksi
                        @if ($sortField === 'transaction_code')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('amount')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Jumlah
                        @if ($sortField === 'amount')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('payment_method')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Metode Pembayaran
                        @if ($sortField === 'payment_method')
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
                    <th wire:click="sortBy('paid_at')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer">
                        Paid At
                        @if ($sortField === 'paid_at')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4">{{ $transaction->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->midtrans_order_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp
                            {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $transaction->payment_type ? ucwords(str_replace('_', ' ', $transaction->payment_type)) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $transaction->status === 'success'
                                    ? 'bg-green-100 text-green-800'
                                    : ($transaction->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
