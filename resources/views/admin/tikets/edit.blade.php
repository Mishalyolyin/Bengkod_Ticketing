<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Tiket • {{ $event->judul }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="{{ route('admin.events.tikets.update', [$event, $tiket]) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe</label>
                        <select name="tipe" class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
                            <option value="reguler" @selected(old('tipe', $tiket->tipe)==='reguler')>Reguler</option>
                            <option value="premium" @selected(old('tipe', $tiket->tipe)==='premium')>Premium</option>
                        </select>
                        @error('tipe') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" name="harga" value="{{ old('harga', $tiket->harga) }}"
                               class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
                        @error('harga') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $tiket->stok) }}"
                               class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
                        @error('stok') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2 flex gap-2">
                        <button class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                            Update
                        </button>
                        <a href="{{ route('admin.events.tikets.index', $event) }}"
                           class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
