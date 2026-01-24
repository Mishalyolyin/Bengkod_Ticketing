<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 transition">Admin</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-gray-700 font-medium">Riwayat Order</span>
                </div>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-900">
                    Riwayat Order
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Hanya transaksi yang sudah <span class="font-semibold text-gray-700">success (paid)</span> yang muncul di sini.
                </p>
            </div>

            <form method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 102.83 6.83l3.17 3.17a1 1 0 001.41-1.41l-3.17-3.17A4 4 0 008 4zm-2 4a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd" />
                        </svg>
                    </span>

                    <input type="text" name="q" value="{{ $q }}"
                           class="input pl-10 w-72"
                           placeholder="Cari: buyer / event / metode / id...">
                </div>

                <button class="btn-soft" type="submit">Cari</button>
                <a href="{{ route('admin.orders.index') }}" class="btn-ghost">Reset</a>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5 relative">
            <div class="pointer-events-none absolute inset-x-0 -top-6 -z-10 h-40 bg-gradient-to-b from-indigo-50/70 to-transparent"></div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="card p-5">
                    <div class="text-xs text-gray-500">Total Order (Paid)</div>
                    <div class="mt-1 text-2xl font-extrabold text-gray-900">{{ $orders->total() }}</div>
                    <div class="mt-1 text-xs text-gray-400">Transaksi valid yang sudah sukses</div>
                </div>

                <div class="card p-5">
                    <div class="text-xs text-gray-500">Total Revenue</div>
                    <div class="mt-1 text-2xl font-extrabold text-gray-900">
                        Rp {{ number_format((float)$totalRevenue, 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-xs text-gray-400">Akumulasi dari transaksi paid</div>
                </div>

                <div class="card p-5">
                    <div class="text-xs text-gray-500">Halaman</div>
                    <div class="mt-1 text-2xl font-extrabold text-gray-900">
                        {{ $orders->currentPage() }}<span class="text-gray-400">/{{ $orders->lastPage() }}</span>
                    </div>
                    <div class="mt-1 text-xs text-gray-400">Pagination data admin</div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card overflow-hidden">
                <div class="px-4 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-gray-900">Daftar Transaksi</div>
                        <div class="text-sm text-gray-500">Klik “Detail” untuk lihat rincian tiket + metode.</div>
                    </div>

                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Only Paid
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                                <th class="py-3 px-4">Order</th>
                                <th class="py-3 px-4">Buyer</th>
                                <th class="py-3 px-4">Event</th>
                                <th class="py-3 px-4">Metode</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Dibayar</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $o)
                                @php
                                    $status = strtolower($o->status ?? 'paid');
                                    $isPaid = $status === 'paid';
                                    $paidAt = $o->paid_at ?? $o->order_date ?? $o->created_at;
                                @endphp

                                <tr class="border-t border-gray-100 hover:bg-indigo-50/40 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-extrabold text-gray-900">#{{ $o->id }}</div>
                                        <div class="text-xs text-gray-500">INV-{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-900">{{ $o->user?->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 hidden sm:block">{{ $o->user?->email ?? '' }}</div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-900">{{ $o->event?->judul ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $o->event?->kategori?->nama ?? '' }}
                                            @if($o->event?->lokasi)
                                                • {{ $o->event->lokasi }}
                                            @endif
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $o->paymentType?->name ?? 'Belum dipilih' }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                            {{ $isPaid ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                            {{ strtoupper($o->status ?? 'paid') }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-gray-600">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-800">
                                                {{ $paidAt?->format('d M Y H:i') }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ $paidAt?->diffForHumans() }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 font-extrabold text-gray-900">
                                        Rp {{ number_format((float)$o->total_price, 0, ',', '.') }}
                                    </td>

                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('admin.orders.show', $o) }}"
                                           class="btn-ghost inline-flex items-center gap-2">
                                            Detail <span>→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t border-gray-100">
                                    <td colspan="8" class="py-14 text-center">
                                        <div class="text-gray-800 font-extrabold">Belum ada transaksi paid</div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            Biasanya karena buyer belum klik “Bayar Sekarang” 😭
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
