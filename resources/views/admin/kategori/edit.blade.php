<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">Edit Kategori</h2>
                <p class="text-sm text-gray-500 mt-1">Update nama kategori tanpa drama.</p>
            </div>
            <a href="{{ route('admin.kategori.index') }}"
               class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold
                      bg-white border border-slate-200 text-gray-700 hover:bg-slate-50 transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur border border-white/60 shadow-sm rounded-2xl p-6">
                <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Nama Kategori</label>
                        <input
                            name="nama"
                            value="{{ old('nama', $kategori->nama) }}"
                            placeholder="Contoh: Festival, Seminar…"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-900
                                   shadow-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                            required
                        />
                        @error('nama')
                            <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.kategori.index') }}"
                           class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold
                                  bg-white border border-slate-200 text-gray-700 hover:bg-slate-50 transition">
                            Batal
                        </a>

                        <button
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
                                   bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-sm
                                   hover:shadow-md hover:brightness-110 transition
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200">
                            Update
                        </button>
                    </div>
                </form>
            </div>

            <div class="text-xs text-slate-500 mt-3 px-1">
                ID kategori: <span class="font-semibold text-slate-700">{{ $kategori->id }}</span>
            </div>
        </div>
    </div>
</x-app-layout>
