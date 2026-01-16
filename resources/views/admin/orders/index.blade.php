<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 leading-tight">Transaksi</h2>
                <p class="text-sm text-slate-500 mt-0.5">Monitoring pembelian tiket oleh user.</p>
            </div>

            <form method="GET" class="flex items-center gap-2">
                <input name="q" value="{{ $q ?? '' }}" class="input w-72" placeholder="Cari user / event...">
                <button class="btn-primary">Search</button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="card p-5">
                    <div class="text-xs text-slate-500">Total Transaksi</div>
                    <div class="text-2xl font-extrabold text-ink mt-1">{{ $orders->total() }}</div>
                </div>
                <div class="card p-5 md:col-span-2">
                    <div class="text-xs text-slate-500">Total Revenue</div>
                    <div class="text-2xl font-extrabold text-ink mt-1">
                        Rp {{ number_format((int)($totalRevenue ?? 0), 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="card p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/70">
                            <tr class="text-left text-slate-500 border-b">
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Event</th>
                                <th class="py-3 px-4">User</th>
                                <th class="py-3 px-4">Item</th>
                                <th class="py-3 px-4 text-right">Total</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $o)
                                <tr class="border-b last:border-0">
                                    <td class="py-3 px-4 text-slate-600">
                                        {{ optional($o->order_date)->format('d M Y H:i') ?? $o->created_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-ink">
                                        {{ $o->event?->judul ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">
                                        <div class="font-semibold text-ink">{{ $o->user?->name ?? '-' }}</div>
                                        <div class="text-xs">{{ $o->user?->email ?? '' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">
                                        {{ $o->detailOrders->sum('jumlah') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-extrabold text-ink">
                                        Rp {{ number_format((int)$o->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('admin.orders.show', $o) }}" class="btn-primary px-4 py-2">
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 px-4 text-center text-slate-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {{ $orders->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
