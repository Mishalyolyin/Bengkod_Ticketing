<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 leading-tight">Riwayat Pesanan</h2>
                <p class="text-sm text-slate-500 mt-0.5">Semua transaksi tiket kamu tersimpan rapi di sini.</p>
            </div>
            <a href="{{ route('public.events.index') }}" class="btn-primary">
                Cari Event
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="card p-5 border border-emerald-200 bg-emerald-50/70">
                    <div class="font-extrabold text-emerald-800">Berhasil ✅</div>
                    <div class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</div>
                </div>
            @endif

            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Total pesanan:
                        <span class="font-extrabold text-ink">{{ $orders->total() }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($orders as $o)
                    <div class="card p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-xs text-slate-500">
                                    {{ optional($o->order_date)->format('d M Y H:i') ?? $o->created_at?->format('d M Y H:i') }}
                                </div>

                                <div class="text-lg font-extrabold text-ink truncate mt-1">
                                    {{ $o->event?->judul ?? 'Event' }}
                                </div>

                                <div class="text-sm text-slate-600 mt-1">
                                    Total:
                                    <span class="font-extrabold text-ink">Rp {{ number_format((int)$o->total_price, 0, ',', '.') }}</span>
                                </div>

                                <div class="text-xs text-slate-500 mt-1">
                                    Item: {{ $o->detailOrders->sum('jumlah') }}
                                </div>

                                {{-- NEW: Metode Pembayaran --}}
                                <div class="mt-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        Metode:
                                        <span class="ml-1 text-slate-900">
                                            {{ $o->paymentType?->name ?? 'Belum dipilih' }}
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('buyer.orders.show', $o) }}"
                               class="btn-primary px-4 py-2">
                                Detail
                                <span class="ml-1">→</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="card p-10 text-center lg:col-span-2">
                        <div class="text-lg font-extrabold text-ink">Belum ada pesanan</div>
                        <div class="text-sm text-slate-500 mt-1">Mulai dari explore event dulu, nanti transaksi kamu muncul di sini.</div>
                        <div class="mt-5">
                            <a href="{{ route('public.events.index') }}" class="btn-primary">Explore Events</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div>
                {{ $orders->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
