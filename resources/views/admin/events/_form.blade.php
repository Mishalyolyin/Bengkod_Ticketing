@csrf

<div class="space-y-6">

    {{-- Kategori --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="kategori_id" class="input" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}"
                    @selected(old('kategori_id', $event->kategori_id ?? '') == $k->id)>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Judul --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="judul" class="input"
               value="{{ old('judul', $event->judul ?? '') }}" required>
        @error('judul') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="5" class="input">{{ old('deskripsi', $event->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Lokasi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Lokasi</label>
            <select name="lokasi_id" class="input" required>
                <option value="">-- Pilih Lokasi --</option>
                @foreach($lokasis as $l)
                    <option value="{{ $l->id }}"
                        @selected(old('lokasi_id', $event->lokasi_id ?? '') == $l->id)>
                        {{ $l->nama_lokasi }}
                    </option>
                @endforeach
            </select>
            @error('lokasi_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Kota --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Kota</label>
            <input type="text" name="kota" class="input"
                   value="{{ old('kota', $event->kota ?? '') }}" required>
            @error('kota') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Waktu --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Waktu</label>
        <input type="datetime-local" name="waktu" class="input"
               value="{{ old('waktu', isset($event) ? optional($event->waktu)->format('Y-m-d\TH:i') : '') }}"
               required>
        @error('waktu') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Poster --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Poster (optional)</label>
        <input type="file" name="gambar" class="input">
        @error('gambar') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Tombol --}}
    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('admin.events.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
            Batal
        </a>
        <button type="submit"
                class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black transition">
            Simpan
        </button>
    </div>

</div>
