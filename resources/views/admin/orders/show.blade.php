<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-gray-700 transition">Transaksi</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-gray-700 font-medium">Detail #{{ $order->id }}</span>
                </div>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-900">
                    Detail Transaksi #{{ $order->id }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Event:
                    <span class="font-semibold text-gray-800">{{ $order->event?->judul ?? '-' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if(Route::has('admin.events.edit') && $order->event)
                    <a href="{{ route('admin.events.edit', $order->event) }}" class="btn-soft">Kelola Event</a>
                @endif
                <a href="{{ route('admin.orders.index') }}" class="btn-ghost">← Kembali</a>
            </div>
        </div>
    </x-slot>

    @php
        $status = strtolower($order->status ?? 'paid');
        $isPaid = $status === 'paid';
        $paidAt = $order->paid_at ?? $order->order_date ?? $order->created_at;
        $invoice = 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
    @endphp

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5 relative">
            <div class="pointer-events-none absolute inset-x-0 -top-6 -z-10 h-40 bg-gradient-to-b from-indigo-50/70 to-transparent"></div>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div class="card p-5 md:col-span-2">
                    <div class="text-xs text-gray-500">Nomor Invoice</div>
                    <div class="mt-1 font-extrabold text-gray-900 text-lg">{{ $invoice }}</div>
                    <div class="text-xs text-gray-500 mt-1">ID Transaksi: #{{ $order->id }}</div>
                </div>

                <div class="card p-5 md:col-span-2">
                    <div class="text-xs text-gray-500">Pembeli</div>
                    <div class="mt-1 font-extrabold text-gray-900">{{ $order->user?->name ?? '-' }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $order->user?->email ?? '-' }}</div>
                </div>

                <div class="card p-5">
                    <div class="text-xs text-gray-500">Status Transaksi</div>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $isPaid ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                            {{ strtoupper($order->status ?? 'paid') }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-400 mt-2">Menampilkan transaksi yang telah tercatat.</div>
                </div>

                <div class="card p-5">
                    <div class="text-xs text-gray-500">Total Pembayaran</div>
                    <div class="mt-1 font-extrabold text-gray-900 text-lg">
                        Rp {{ number_format((float)$order->total_price, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        Waktu bayar: {{ $paidAt?->format('d M Y H:i') ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Informasi Event + Metode --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                <div class="card p-5 lg:col-span-2">
                    <div class="font-extrabold text-gray-900">Informasi Event</div>
                    <div class="text-sm text-gray-500 mt-1">Rincian event yang dibeli pada transaksi ini.</div>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="p-4 rounded-xl border border-gray-100 bg-white">
                            <div class="text-xs text-gray-500">Judul Event</div>
                            <div class="mt-1 font-extrabold text-gray-900">{{ $order->event?->judul ?? '-' }}</div>
                        </div>

                        <div class="p-4 rounded-xl border border-gray-100 bg-white">
                            <div class="text-xs text-gray-500">Kategori</div>
                            <div class="mt-1 font-extrabold text-gray-900">{{ $order->event?->kategori?->nama ?? '-' }}</div>
                        </div>

                        <div class="p-4 rounded-xl border border-gray-100 bg-white">
                            <div class="text-xs text-gray-500">Lokasi</div>
                            <div class="mt-1 font-extrabold text-gray-900">{{ $order->event?->lokasi ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <div class="font-extrabold text-gray-900">Metode Pembayaran</div>
                    <div class="text-sm text-gray-500 mt-1">Metode yang dipilih oleh pembeli saat checkout.</div>

                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $order->paymentType?->name ?? 'Belum dipilih' }}
                        </span>
                    </div>

                    <div class="mt-4 text-xs text-gray-500">
                        Waktu pembayaran:
                        <span class="font-semibold text-gray-800">{{ $paidAt?->format('d M Y H:i') ?? '-' }}</span>
                        <div class="text-gray-400">{{ $paidAt?->diffForHumans() ?? '' }}</div>
                    </div>
                </div>
            </div>

            {{-- Rincian Tiket --}}
            <div class="card overflow-hidden">
                <div class="px-4 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-gray-900">Rincian Tiket</div>
                        <div class="text-sm text-gray-500">Daftar tiket yang dibeli dalam transaksi ini.</div>
                    </div>

                    <div class="text-xs text-gray-500">
                        Total item: <span class="font-semibold text-gray-800">{{ $order->details->sum('jumlah') }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                                <th class="py-3 px-4">Tipe Tiket</th>
                                <th class="py-3 px-4">Harga</th>
                                <th class="py-3 px-4">Jumlah</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($order->details as $d)
                                <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ strtoupper($d->tiket?->tipe ?? 'TIKET') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">
                                        Rp {{ number_format((float)($d->price ?? $d->tiket?->harga ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">{{ (int)$d->jumlah }}</td>
                                    <td class="py-3 px-4 text-right font-extrabold text-gray-900">
                                        Rp {{ number_format((float)$d->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="border-t border-gray-100">
                                <td colspan="3" class="py-4 px-4 text-right font-semibold text-gray-600">Total</td>
                                <td class="py-4 px-4 text-right font-extrabold text-gray-900">
                                    Rp {{ number_format((float)$order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="px-4 py-4 border-t border-gray-100 flex items-center justify-end gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn-ghost">Kembali ke Daftar</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
