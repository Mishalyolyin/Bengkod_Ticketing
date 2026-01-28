<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Lokasi</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                @if(session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.lokasi.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi') }}"
                               class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400"
                               placeholder="Contoh: Stadion Utama">
                        @error('nama_lokasi') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                            Simpan
                        </button>
                        <a href="{{ route('admin.lokasi.index') }}"
                           class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
