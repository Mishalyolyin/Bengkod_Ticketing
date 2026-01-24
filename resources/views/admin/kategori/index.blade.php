<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Kategori Event
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola kategori 
                </p>
            </div>

            <a href="{{ route('admin.kategori.create') }}"
               class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
                      bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-sm
                      hover:shadow-md hover:brightness-110 transition
                      focus:outline-none focus:ring-4 focus:ring-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 shadow-sm">
                    <div class="font-semibold">Berhasil ✅</div>
                    <div class="text-sm mt-1">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-800 shadow-sm">
                    <div class="font-semibold">Oops 😭</div>
                    <div class="text-sm mt-1">{{ session('error') }}</div>
                </div>
            @endif

            {{-- Stats + Search --}}
            <div class="bg-white/80 backdrop-blur border border-white/60 shadow-sm rounded-2xl p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5">
                        <div class="text-xs text-slate-500">Total Kategori</div>
                        <div class="mt-1 text-2xl font-extrabold text-gray-900">
                            {{ $total ?? $kategoris->total() }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-indigo-50 to-white p-5">
                        <div class="text-xs text-slate-500">Sedang Ditampilkan</div>
                        <div class="mt-1 text-2xl font-extrabold text-gray-900">
                            {{ $kategoris->count() }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-purple-50 to-white p-5">
                        <div class="text-xs text-slate-500">Cari Cepat</div>

                        <form method="GET" class="mt-3 flex gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="M21 21l-4.3-4.3"></path>
                                    </svg>
                                </div>
                                <input
                                    name="q"
                                    value="{{ $q ?? request('q') }}"
                                    placeholder="Cari nama kategori…"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-10 py-2 text-sm text-gray-900
                                           shadow-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                />
                            </div>

                            <button class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
                                           bg-gray-900 text-white hover:bg-gray-800 transition
                                           focus:outline-none focus:ring-4 focus:ring-gray-200">
                                Search
                            </button>

                            <a href="{{ route('admin.kategori.index') }}"
                               class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold
                                      bg-white border border-slate-200 text-gray-700 hover:bg-slate-50 transition">
                                Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white/80 backdrop-blur border border-white/60 shadow-sm rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-gray-900">Daftar Kategori</div>
                        <div class="text-sm text-slate-500">Edit / hapus kategori dengan aman.</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/60">
                            <tr class="text-left text-xs font-semibold text-slate-600">
                                <th class="px-6 py-3 w-16">No</th>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3 w-40">Jumlah Event</th>
                                <th class="px-6 py-3 w-40">Dibuat</th>
                                <th class="px-6 py-3 w-44 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($kategoris as $i => $k)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $i + 1 }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $k->nama }}</div>
                                        <div class="text-xs text-slate-500">ID: {{ $k->id }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                     bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $k->events_count ?? '—' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ optional($k->created_at)->format('d M Y') ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.kategori.edit', $k) }}"
                                               class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold
                                                      bg-white border border-slate-200 text-gray-700 hover:bg-slate-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.kategori.destroy', $k) }}" method="POST"
                                                  onsubmit="return confirm('Yakin hapus kategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold
                                                           bg-rose-600 text-white hover:bg-rose-700 transition
                                                           focus:outline-none focus:ring-4 focus:ring-rose-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4h8v2"/>
                                                        <path d="M19 6l-1 14H6L5 6"/>
                                                        <path d="M10 11v6M14 11v6"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-14 text-center">
                                        <div class="text-lg font-extrabold text-gray-900">Belum ada kategori</div>
                                        <div class="text-sm text-slate-500 mt-1">
                                            Bikin dulu biar event kamu punya “rumah” 🏠
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.kategori.create') }}"
                                               class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
                                                      bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-sm
                                                      hover:shadow-md hover:brightness-110 transition">
                                                Tambah Kategori
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $kategoris->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
