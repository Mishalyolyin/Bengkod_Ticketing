<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Management Lokasi</h2>

            <a href="{{ route('admin.lokasi.create') }}"
               class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                + Tambah Lokasi
            </a>
        </div>
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
                <input type="text" name="q" value="{{ $q }}"
                       class="w-full sm:w-72 rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400"
                       placeholder="Cari nama lokasi...">

                <button class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                    Filter
                </button>

                <a href="{{ route('admin.lokasi.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                    Reset
                </a>
            </form>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left p-3 w-24">ID</th>
                            <th class="text-left p-3">Nama Lokasi</th>
                            <th class="text-left p-3 w-64">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($lokasis as $lokasi)
                            <tr class="border-t">
                                <td class="p-3 font-semibold">{{ $lokasi->id }}</td>
                                <td class="p-3">
                                    <div class="font-semibold text-gray-900">{{ $lokasi->nama_lokasi }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $lokasi->created_at?->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.lokasi.edit', $lokasi) }}"
                                           class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.lokasi.destroy', $lokasi) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus lokasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray-500">
                                    Belum ada lokasi. Tambah dulu ya 😭
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $lokasis->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
