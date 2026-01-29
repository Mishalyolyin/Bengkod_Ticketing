<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 leading-tight">Riwayat Orders</h2>
                <p class="text-sm text-slate-500 mt-0.5">Semua pesanan kamu, lengkap sama lokasi eventnya.</p>
            </div>
            <a href="{{ route('public.events.index') }}" class="btn-primary">+ Beli Tiket Lagi</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-4">

            @if(session('success'))
                <div class="card p-4 border border-emerald-200 bg-emerald-50/60 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="card p-4 border border-rose-200 bg-rose-50/60 text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card overflow-hidden">
                <div class="px-4 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-gray-900">Daftar Order</div>
                        <div class="text-sm text-gray-500">Klik “Detail” buat lihat tiket + status + metode pembayaran.</div>
                    </div>
                    <span class="badge-soft">Orders</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                                <th class="py-3 px-4">Order</th>
                                <th class="py-3 px-4">Event</th>
                                <th class="py-3 px-4">Lokasi</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $o)
                                @php
                                    $status = strtolower($o->status ?? 'pending');
                                    $isPaid = $status === 'paid';
                                    $invoice = 'INV-' . str_pad($o->id, 5, '0', STR_PAD_LEFT);
                                @endphp

                                <tr class="border-t border-gray-100 hover:bg-indigo-50/40 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-extrabold text-gray-900">{{ $invoice }}</div>
                                        <div class="text-xs text-gray-500">#{{ $o->id }}</div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-900">{{ $o->event?->judul ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ optional($o->order_date)->format('d M Y H:i') ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-gray-700">
                                        <div>📍 {{ $o->event?->lokasi ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 ml-5">{{ $o->event?->kota }}</div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                            {{ $isPaid ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                            {{ strtoupper($o->status ?? 'PENDING') }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 font-extrabold text-gray-900">
                                        Rp {{ number_format((int)$o->total_price, 0, ',', '.') }}
                                    </td>

                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('buyer.orders.show', $o) }}" class="btn-ghost inline-flex items-center gap-2">
                                            Detail <span>→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t border-gray-100">
                                    <td colspan="6" class="py-14 text-center">
                                        <div class="text-gray-800 font-extrabold">Belum ada order</div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            Silakan cari dan pesan tiket event terlebih dahulu.
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('public.events.index') }}" class="btn-primary">Jelajahi Event</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
