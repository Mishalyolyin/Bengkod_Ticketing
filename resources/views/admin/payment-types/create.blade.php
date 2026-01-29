<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <a href="{{ route('admin.payment-types.index') }}" class="hover:text-gray-700 transition">Tipe Pembayaran</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-gray-700 font-medium">Tambah</span>
                </div>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-900">Tambah Tipe Pembayaran</h2>
                <p class="mt-1 text-sm text-gray-500">Contoh: Transfer Bank, E-Wallet, Cash.</p>
            </div>

            <a href="{{ route('admin.payment-types.index') }}" class="btn-ghost">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="card border border-red-200 bg-red-50/70 mb-4">
                    <div class="font-semibold text-red-800">Ada yang perlu dibenerin dulu 😅</div>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                {{-- Tips --}}
                <div class="card lg:col-span-1">
                    <div class="font-semibold text-gray-800">Tips</div>
                    <p class="text-sm text-gray-500 mt-1">
                        Gunakan nama yang konsisten dan singkat agar lebih rapi.
                    </p>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex items-start gap-2">
                            <span class="mt-0.5 text-emerald-600">●</span>
                            <span><b>Unik</b> (tidak boleh duplikat)</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="mt-0.5 text-emerald-600">●</span>
                            <span>Contoh penulisan: <b>Transfer Bank</b>, <b>E-Wallet</b>, <b>Cash</b></span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="mt-0.5 text-emerald-600">●</span>
                            <span>Tipe pembayaran ini akan digunakan saat checkout.</span>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="card lg:col-span-2">
                    <form method="POST" action="{{ route('admin.payment-types.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Nama Tipe Pembayaran</label>
                            <div class="mt-1">
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="input w-full @error('name') border-red-300 @enderror"
                                       placeholder="Contoh: Transfer Bank">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nama ini tampil di list & (opsional) nanti dipakai saat transaksi.</p>
                            @error('name')
                                <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <a href="{{ route('admin.payment-types.index') }}" class="btn-ghost">Batal</a>
                            <button type="submit" class="btn-primary inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.5 7.5a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414L8.5 11.086l6.793-6.793a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
