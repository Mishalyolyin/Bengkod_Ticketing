<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tiket • {{ $event->judul }}</h2>
                <div class="text-sm text-gray-500">{{ $event->lokasi }} • {{ $event->waktu?->format('d M Y H:i') }}</div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.events.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                    ← Kembali
                </a>

                <a href="{{ route('admin.events.tikets.create', $event) }}"
                   class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                    + Tambah Tiket
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left p-3">Tipe</th>
                            <th class="text-right p-3">Harga</th>
                            <th class="text-center p-3">Stok</th>
                            <th class="text-center p-3">Terjual</th>
                            <th class="text-center p-3">Sisa</th>
                            <th class="text-left p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tikets as $t)
                            @php
                                $sold = (int) ($t->detail_orders_sum_jumlah ?? 0);
                                $sisa = max(0, (int)$t->stok - $sold);
                            @endphp
                            <tr class="border-t">
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">
                                        {{ strtoupper($t->tipe) }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-semibold">
                                    Rp {{ number_format($t->harga, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-center font-semibold">{{ $t->stok }}</td>
                                <td class="p-3 text-center font-semibold">{{ $sold }}</td>
                                <td class="p-3 text-center font-semibold">{{ $sisa }}</td>
                                <td class="p-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.events.tikets.edit', [$event, $t]) }}"
                                           class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.events.tikets.destroy', [$event, $t]) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus tiket ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    @if($sold > 0)
                                        <div class="text-xs text-gray-500 mt-2">
                                            *Sudah ada transaksi → sebaiknya jangan dihapus.
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-500">
                                    Belum ada tiket untuk event ini. Silakan tambah tiket baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tikets->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
