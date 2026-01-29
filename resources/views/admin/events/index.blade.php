<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Event</h2>

            <div class="flex items-center gap-2">
                {{-- pilih event untuk tambah tiket --}}
                <select id="quickEvent"
                        class="hidden sm:block w-72 rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
                    <option value="">Pilih event untuk tiket...</option>
                    @foreach($allEvents as $ev)
                        <option value="{{ $ev->id }}">
                            {{ $ev->judul }} ({{ $ev->waktu?->format('d M Y') }})
                        </option>
                    @endforeach
                </select>

                {{-- tombol tambah tiket --}}
                <button type="button" id="btnQuickTiket"
                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-900 hover:bg-gray-200 transition border border-gray-200">
                    + Tambah Tiket
                </button>

                {{-- tombol tambah event --}}
                <a href="{{ route('admin.events.create') }}"
                   class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                    + Tambah Event
                </a>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sel = document.getElementById('quickEvent');
                const btn = document.getElementById('btnQuickTiket');
                if (!sel || !btn) return;

                btn.addEventListener('click', function () {
                    const id = sel.value;
                    if (!id) {
                        alert('Pilih event dulu ya 😭');
                        sel.focus();
                        return;
                    }

                    const base = @json(url('/admin/events'));
                    window.location.href = `${base}/${id}/tikets/create`;
                });
            });
        </script>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            <form method="GET"
                  class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-3 items-center">
                <input type="text" name="q" value="{{ request('q') }}"
                       class="w-full sm:w-72 rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400"
                       placeholder="Cari judul / lokasi...">

                <select name="kategori"
                        class="w-full sm:w-60 rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>

                <button class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                    Filter
                </button>

                <a href="{{ route('admin.events.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                    Reset
                </a>
            </form>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left p-3">Poster</th>
                            <th class="text-left p-3">Event</th>
                            <th class="text-left p-3">Kategori</th>
                            <th class="text-left p-3">Waktu</th>
                            <th class="text-center p-3">Tiket</th>
                            <th class="text-center p-3">Transaksi</th>
                            <th class="text-left p-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($events as $event)
                            <tr class="border-t">
                                <td class="p-3">
                                    @if($event->gambar)
                                        <img src="{{ asset('storage/'.$event->gambar) }}"
                                             class="h-12 w-20 object-cover rounded-lg border" alt="poster">
                                    @else
                                        <div class="h-12 w-20 rounded-lg bg-gray-100 flex items-center justify-center text-xs text-gray-500 border">
                                            No Poster
                                        </div>
                                    @endif
                                </td>

                                <td class="p-3">
                                    <div class="font-semibold text-gray-900">{{ $event->judul }}</div>
                                    <div class="text-xs text-gray-500">{{ $event->lokasi }} ({{ $event->kota }})</div>
                                </td>

                                <td class="p-3">
                                    <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">
                                        {{ $event->kategori?->nama ?? '-' }}
                                    </span>
                                </td>

                                <td class="p-3">
                                    <div class="font-semibold text-gray-900">{{ $event->waktu?->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $event->waktu?->format('H:i') }} WIB</div>
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 font-semibold text-xs">
                                        {{ $event->tikets_count }}
                                    </span>
                                </td>

                                <td class="p-3 text-center font-semibold">
                                    {{ $event->orders_count }}
                                </td>

                                <td class="p-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.events.tikets.index', $event) }}"
                                           class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                                            Kelola Tiket
                                        </a>

                                        <a href="{{ route('admin.events.edit', $event) }}"
                                           class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus event ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    @if($event->orders_count > 0)
                                        <div class="text-xs text-gray-500 mt-2">
                                            *Event memiliki transaksi aktif, tidak dapat dihapus.
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500">
                                    Belum ada event. Silakan tambah event baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $events->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
