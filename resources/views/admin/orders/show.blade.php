<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 leading-tight">Detail Transaksi</h2>
                <p class="text-sm text-slate-500 mt-0.5">Rincian pembelian + data user.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="card p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">User</div>
                        <div class="font-extrabold text-ink">{{ $order->user?->name ?? '-' }}</div>
                        <div class="text-sm text-slate-600">{{ $order->user?->email ?? '-' }}</div>
                    </div>
                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Event</div>
                        <div class="font-extrabold text-ink">{{ $order->event?->judul ?? '-' }}</div>
                        <div class="text-sm text-slate-600">
                            {{ $order->event?->lokasi ?? '-' }} • {{ optional($order->event?->waktu)->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Tanggal Order</div>
                        <div class="font-extrabold text-ink">
                            {{ optional($order->order_date)->format('d M Y H:i') ?? $order->created_at?->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Jumlah Tiket</div>
                        <div class="font-extrabold text-ink">{{ $order->detailOrders->sum('jumlah') }}</div>
                    </div>
                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Total</div>
                        <div class="font-extrabold text-ink">Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-lg font-extrabold text-ink mb-4">Rincian Tiket</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b">
                                <th class="py-2">Tiket</th>
                                <th class="py-2">Harga</th>
                                <th class="py-2">Qty</th>
                                <th class="py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->detailOrders as $d)
                                <tr class="border-b last:border-0">
                                    <td class="py-3 font-semibold text-ink">{{ $d->tiket?->nama ?? '-' }}</td>
                                    <td class="py-3 text-slate-600">Rp {{ number_format((int)($d->tiket?->harga ?? 0), 0, ',', '.') }}</td>
                                    <td class="py-3 text-slate-600">{{ (int)$d->jumlah }}</td>
                                    <td class="py-3 text-right font-extrabold text-ink">Rp {{ number_format((int)$d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-4 text-right font-semibold text-slate-600">Total</td>
                                <td class="pt-4 text-right font-extrabold text-ink">
                                    Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
