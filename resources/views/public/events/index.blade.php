<x-guest-layout>
    <section class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <div class="text-xs text-slate-500">Public / Events</div>
                <h1 class="text-3xl font-extrabold text-ink mt-1">Jelajahi Event</h1>
                <p class="text-sm text-slate-600 mt-1">
                    Cari event berdasarkan judul atau lokasi. Temukan berbagai event menarik di sekitar Anda.
                </p>
            </div>

            <form action="{{ route('public.events.index') }}" method="GET" class="card p-4 w-full md:w-auto">
                <div class="flex flex-col sm:flex-row gap-2">
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        class="input"
                        placeholder="Cari judul / lokasi…"
                    />

                    <select name="kategori" class="input sm:w-56">
                        <option value="">Semua kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" @selected((string)$kategoriId === (string)$k->id)>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>

                    <button class="btn-primary" type="submit">🔎 Filter</button>
                    <a href="{{ route('public.events.index') }}" class="btn-ghost text-center">Reset</a>
                </div>
            </form>
        </div>

        {{-- results info --}}
        <div class="flex items-center justify-between text-sm text-slate-500">
            <div>
                Menampilkan <span class="font-semibold text-ink">{{ $events->count() }}</span> dari
                <span class="font-semibold text-ink">{{ $events->total() }}</span> event
            </div>
            @if($q !== '')
                <div>
                    Keyword: <span class="font-semibold text-ink">"{{ $q }}"</span>
                </div>
            @endif
        </div>

        {{-- grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
                @php
                    $minPrice = $event->tikets->min('harga');
                    $stokTotal = (int) $event->tikets->sum('stok');
                @endphp

                <div class="card overflow-hidden">
                    <div class="h-40 bg-slate-100">
                        @if($event->gambar)
                            <img src="{{ asset('storage/'.$event->gambar) }}" class="w-full h-40 object-cover" alt="poster">
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <span class="badge-soft">{{ $event->kategori?->nama ?? '-' }}</span>
                            <div class="text-xs text-slate-500">Stok {{ $stokTotal }}</div>
                        </div>

                        <div class="mt-3 text-lg font-extrabold text-ink">
                            {{ $event->judul }}
                        </div>

                        <div class="mt-2 text-sm text-slate-600 space-y-1">
                            <div>📍 <span class="font-semibold text-slate-700">{{ $event->lokasi ?? '-' }}</span></div>
                            <div class="text-xs text-slate-500 ml-5">{{ $event->kota }}</div>
                            <div>🗓️ {{ optional($event->waktu)->format('D, d M Y') ?? '-' }}
                                <span class="text-slate-400">•</span>
                                {{ optional($event->waktu)->format('H:i') ?? '-' }}
                            </div>
                        </div>

                        <p class="mt-3 text-sm text-slate-600 line-clamp-2">
                            {{ $event->deskripsi }}
                        </p>

                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <div class="text-xs text-slate-500">Mulai dari</div>
                                <div class="font-extrabold text-ink">
                                    Rp {{ number_format($minPrice ?? 0, 0, ',', '.') }}
                                </div>
                            </div>

                            <a href="{{ route('public.events.show', $event) }}" class="btn-primary">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card p-10 text-center col-span-full">
                    <div class="text-ink font-extrabold">Event belum ketemu</div>
                    <div class="text-sm text-slate-600 mt-1">
                        Coba ganti keyword, atau reset filternya.
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('public.events.index') }}" class="btn-primary">Reset</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $events->links() }}
        </div>
    </section>
</x-guest-layout>
