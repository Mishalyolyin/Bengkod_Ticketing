<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <a href="{{ route('admin.payment-types.index') }}" class="hover:text-gray-700 transition">Tipe Pembayaran</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-gray-700 font-medium">Edit</span>
                </div>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-900">Edit Tipe Pembayaran</h2>
                <p class="mt-1 text-sm text-gray-500">Update nama tipe pembayaran biar tetap konsisten.</p>
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
                {{-- Info --}}
                <div class="card lg:col-span-1">
                    <div class="font-semibold text-gray-800">Info</div>
                    <p class="text-sm text-gray-500 mt-1">Kamu lagi edit data berikut:</p>

                    <div class="mt-4 space-y-2">
                        <div class="text-xs text-gray-500">Nama saat ini</div>
                        <div class="badge-soft inline-flex">{{ $paymentType->name }}</div>

                        <div class="mt-4 text-xs text-gray-500">ID</div>
                        <div class="text-sm font-semibold text-gray-800">#{{ $paymentType->id }}</div>
                    </div>

                    <div class="mt-5 text-sm text-gray-500">
                        Pro tips: jangan bikin nama baru yang cuma beda spasi doang, nanti kamu sendiri yang pusing 🤝
                    </div>
                </div>

                {{-- Form --}}
                <div class="card lg:col-span-2">
                    <form method="POST" action="{{ route('admin.payment-types.update', $paymentType) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Nama Tipe Pembayaran</label>
                            <div class="mt-1">
                                <input type="text" name="name"
                                       value="{{ old('name', $paymentType->name) }}"
                                       class="input w-full @error('name') border-red-300 @enderror"
                                       placeholder="Contoh: E-Wallet">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Pastikan tetap unik dan mudah dipahami.</p>
                            @error('name')
                                <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <a href="{{ route('admin.payment-types.index') }}" class="btn-ghost">Batal</a>
                            <button type="submit" class="btn-primary inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 010 2.828l-9.9 9.9a1 1 0 01-.39.242l-3.2 1.066a1 1 0 01-1.265-1.265l1.066-3.2a1 1 0 01.242-.39l9.9-9.9a2 2 0 012.828 0z"/>
                                </svg>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
