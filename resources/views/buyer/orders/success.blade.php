<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 leading-tight">Pembayaran Berhasil</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Transaksi kamu sudah tercatat. Simpan invoice ini buat jaga-jaga.
                </p>
            </div>
            <a href="{{ route('buyer.orders.index') }}" class="btn-ghost">← Ke Riwayat</a>
        </div>
    </x-slot>

    @php
        $invoice = 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        $paidAt  = optional($order->paid_at)->format('d M Y H:i');
        $status  = strtoupper($order->status ?? 'PAID');
    @endphp

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Hero Success --}}
            <div class="card p-0 overflow-hidden">
                {{-- top gradient strip --}}
                <div class="h-2 bg-gradient-to-r from-emerald-400 via-teal-400 to-indigo-500"></div>

                <div class="p-8">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.5 7.5a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414L8.5 11.086l6.793-6.793a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-2xl font-extrabold text-ink">
                                        Pembayaran sukses ✅
                                    </h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800">
                                        {{ $status }}
                                    </span>
                                </div>

                                <div class="text-sm text-slate-500 mt-2">
                                    Mantap. Order kamu udah “resmi” dan siap dicek admin.
                                </div>

                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        Invoice: <span class="ml-1 text-slate-900 font-extrabold">{{ $invoice }}</span>
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        Dibayar: <span class="ml-1 text-slate-900 font-extrabold">{{ $paidAt ?: '-' }}</span>
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        Metode: <span class="ml-1 text-slate-900 font-extrabold">{{ $order->paymentType?->name ?? '-' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 w-full md:w-auto">
                            <a href="{{ route('buyer.orders.show', $order) }}" class="btn-primary w-full md:w-auto text-center">
                                Lihat Detail Order
                            </a>
                            <a href="{{ route('buyer.orders.index') }}" class="btn-ghost w-full md:w-auto text-center">
                                Kembali ke Riwayat
                            </a>
                        </div>
                    </div>

                    {{-- Invoice Card --}}
                    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white/70 lg:col-span-2">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs uppercase tracking-wide text-slate-500">Event</div>
                                    <div class="text-xl font-extrabold text-ink mt-1">
                                        {{ $order->event?->judul ?? '-' }}
                                    </div>
                                    <div class="text-sm text-slate-500 mt-1">
                                        Lokasi: <span class="font-semibold text-slate-700">{{ $order->event?->lokasi ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-xs uppercase tracking-wide text-slate-500">Total</div>
                                    <div class="text-2xl font-extrabold text-ink mt-1">
                                        Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-5 border-t border-dashed border-slate-200">
                                <div class="text-xs uppercase tracking-wide text-slate-500 mb-3">Ringkasan Tiket</div>

                                <div class="space-y-2">
                                    @foreach($order->detailOrders as $d)
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="text-sm font-semibold text-ink">
                                                {{ strtoupper($d->tiket?->tipe ?? 'TIKET') }}
                                                <span class="text-slate-500 font-normal">× {{ (int)$d->jumlah }}</span>
                                            </div>
                                            <div class="text-sm font-extrabold text-ink">
                                                Rp {{ number_format((int)$d->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                                    <div class="text-sm font-semibold text-slate-600">Total Dibayar</div>
                                    <div class="text-lg font-extrabold text-ink">
                                        Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Side Panel --}}
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white/70">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Checklist</div>

                            <div class="mt-3 space-y-2 text-sm">
                                <div class="flex items-start gap-2">
                                    <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold">✓</span>
                                    <div class="text-slate-700">
                                        Pembayaran tercatat di sistem
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold">✓</span>
                                    <div class="text-slate-700">
                                        Metode pembayaran tersimpan
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold">✓</span>
                                    <div class="text-slate-700">
                                        Detail tiket bisa dicek di halaman order
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                <div class="text-xs font-semibold text-slate-600">Catatan</div>
                                <div class="text-sm text-slate-600 mt-1">
                                    Screenshot halaman ini kalau perlu bukti cepat.
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col gap-2">
                                <a href="{{ route('public.events.show', $order->event) }}" class="btn-primary w-full text-center">
                                    Kembali ke Event
                                </a>
                                <a href="{{ route('buyer.orders.show', $order) }}" class="btn-ghost w-full text-center">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
