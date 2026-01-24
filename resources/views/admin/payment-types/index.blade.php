<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 transition">Admin</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-gray-700 font-medium">Tipe Pembayaran</span>
                </div>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-900">
                    Tipe Pembayaran
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola metode pembayaran yang tersedia (transfer bank, e-wallet, cash, dll).
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.payment-types.create') }}"
                   class="btn-primary inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                    </svg>
                    <span>Tambah</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5 relative">
            {{-- subtle background glow --}}
            <div class="pointer-events-none absolute inset-x-0 -top-6 -z-10 h-40 bg-gradient-to-b from-indigo-50/70 to-transparent"></div>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="card border border-emerald-200 bg-emerald-50/70">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-emerald-800 font-semibold">Berhasil 🎉</div>
                            <div class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</div>
                        </div>
                        <button type="button" @click="show = false"
                                class="text-emerald-700 hover:text-emerald-900 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            

            {{-- Search --}}
            <div class="card">
                <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                    <div class="flex gap-2 w-full sm:max-w-lg">
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 102.83 6.83l3.17 3.17a1 1 0 001.41-1.41l-3.17-3.17A4 4 0 008 4zm-2 4a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd" />
                                </svg>
                            </span>

                            <input type="text" name="q" value="{{ $q }}"
                                   class="input w-full pl-10"
                                   placeholder="Cari tipe pembayaran... (contoh: Transfer, E-Wallet)">
                        </div>

                        <button class="btn-soft inline-flex items-center gap-2" type="submit">
                            <span>Cari</span>
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.payment-types.index') }}" class="btn-ghost">Reset</a>
                    </div>
                </form>

                @if(!empty($q))
                    <div class="mt-3 text-xs text-gray-500">
                        Hasil untuk: <span class="font-semibold text-gray-700">{{ $q }}</span>
                    </div>
                @endif
            </div>

            {{-- Table --}}
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                                <th class="py-3 px-4 w-16">#</th>
                                <th class="py-3 px-4">Nama</th>
                                <th class="py-3 px-4">Dibuat</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($paymentTypes as $i => $pt)
                                <tr class="border-t border-gray-100 hover:bg-indigo-50/40 transition">
                                    <td class="py-3 px-4 text-gray-500">
                                        {{ $paymentTypes->firstItem() + $i }}
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="badge-soft">{{ $pt->name }}</span>
                                            <span class="text-xs text-gray-400">•</span>
                                            <span class="text-xs text-gray-500">ID: {{ $pt->id }}</span>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-gray-500">
                                        <div class="flex flex-col">
                                            <span>{{ $pt->created_at?->format('d M Y') }}</span>
                                            <span class="text-xs text-gray-400">{{ $pt->created_at?->diffForHumans() }}</span>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.payment-types.edit', $pt) }}"
                                               class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold
                                                      bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 010 2.828l-9.9 9.9a1 1 0 01-.39.242l-3.2 1.066a1 1 0 01-1.265-1.265l1.066-3.2a1 1 0 01.242-.39l9.9-9.9a2 2 0 012.828 0z"/>
                                                </svg>
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('admin.payment-types.destroy', $pt) }}"
                                                  onsubmit="return confirm('Hapus tipe pembayaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold
                                                               bg-red-50 text-red-700 border border-red-100 hover:bg-red-100 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6 8a1 1 0 012 0v7a1 1 0 11-2 0V8zm6-1a1 1 0 00-1 1v7a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        <path d="M4 6h12v2H4V6z"/>
                                                        <path fill-rule="evenodd" d="M8 4a1 1 0 00-1 1v1h6V5a1 1 0 00-1-1H8z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t border-gray-100">
                                    <td colspan="4" class="py-12">
                                        <div class="text-center">
                                            <div class="mx-auto w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 3a2 2 0 00-2 2v2a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v6a2 2 0 002 2h12a2 2 0 002-2V9z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="mt-3 font-semibold text-gray-800">Belum ada tipe pembayaran</div>
                                            <div class="text-sm text-gray-500 mt-1">Klik tombol <b>Tambah</b> buat bikin yang pertama.</div>
                                            <div class="mt-4">
                                                <a href="{{ route('admin.payment-types.create') }}" class="btn-primary inline-flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                                    </svg>
                                                    Tambah Tipe
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-4 border-t border-gray-100">
                    {{ $paymentTypes->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
