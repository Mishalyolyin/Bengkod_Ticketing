@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="kategori_id" class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}"
                    @selected(old('kategori_id', $event->kategori_id ?? '') == $k->id)>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="judul" value="{{ old('judul', $event->judul ?? '') }}"
               class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400"
               placeholder="Contoh: Konser Malam Minggu">
        @error('judul') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="5"
                  class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400"
                  placeholder="Isi deskripsi event...">{{ old('deskripsi', $event->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ✅ FIX: Lokasi dropdown dari tabel lokasis --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Lokasi</label>
        <select name="lokasi" class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
            <option value="">-- Pilih Lokasi --</option>
            @foreach($lokasis as $l)
                <option value="{{ $l->nama_lokasi }}"
                    @selected(old('lokasi', $event->lokasi ?? '') == $l->nama_lokasi)>
                    {{ $l->nama_lokasi }}
                </option>
            @endforeach
        </select>
        @error('lokasi') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Waktu</label>
        <input type="datetime-local" name="waktu"
               value="{{ old('waktu', isset($event) && $event->waktu ? $event->waktu->format('Y-m-d\TH:i') : '') }}"
               class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
        @error('waktu') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Poster (opsional)</label>
        <input type="file" name="gambar"
               class="mt-1 w-full rounded-lg border-gray-200 focus:border-gray-400 focus:ring-gray-400">
        @error('gambar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

        @if(isset($event) && $event->gambar)
            <div class="mt-3">
                <p class="text-xs text-gray-500 mb-1">Poster saat ini:</p>
                <img src="{{ asset('storage/'.$event->gambar) }}" class="h-28 rounded-lg border object-cover" alt="poster">
            </div>
        @endif
    </div>
</div>

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
        Simpan
    </button>
    <a href="{{ route('admin.events.index') }}"
       class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
       Batal
    </a>
</div>
