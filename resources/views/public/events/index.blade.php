<x-guest-layout>
    <div class="space-y-6">
        <div class="card p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-extrabold text-ink">Explore Events</h1>
                    <p class="text-sm text-slate-500">Cari event yang vibes-nya cocok buat kamu.</p>
                </div>
                <a href="{{ route('home') }}" class="btn-ghost">← Home</a>
            </div>

            {{-- ✅ Improved Filter Bar --}}
            <form method="GET" class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <input
                        name="q"
                        value="{{ request('q') }}"
                        class="input"
                        placeholder="Cari judul / lokasi..."
                    >
                </div>

                <div class="md:col-span-4">
                    <select name="kategori" class="input">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" @selected((string)request('kategori') === (string)$k->id)>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <button class="btn-primary w-full h-[44px]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M21 21l-4.3-4.3m1.3-5.2a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                            />
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>

        {{-- ✅ Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($events as $event)
                <a href="{{ route('public.events.show', $event) }}" class="card overflow-hidden group">
                    <div class="h-40 bg-slate-100 overflow-hidden">
                        @if($event->gambar)
                            <img
                                src="{{ asset('storage/'.$event->gambar) }}"
                                class="w-full h-40 object-cover group-hover:scale-[1.03] transition duration-300"
                                alt="poster {{ $event->judul }}"
                                loading="lazy"
                            >
                        @else
                            <div class="w-full h-40 flex items-center justify-center text-slate-500">
                                <div class="text-center">
                                    <div class="text-xs uppercase tracking-wide">No Poster</div>
                                    <div class="font-semibold">
                                        {{ \Illuminate\Support\Str::limit($event->judul, 26) }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="font-extrabold text-ink truncate">{{ $event->judul }}</h3>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $event->lokasi }} • {{ optional($event->waktu)->format('d M Y H:i') }}
                                </div>
                            </div>

                            <span class="badge-soft shrink-0">
                                {{ $event->kategori?->nama ?? '-' }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-600 line-clamp-2">
                            {{ $event->deskripsi }}
                        </p>

                        {{-- ✅ Improved "Detail" actions --}}
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Detail</span>

                            <span class="btn-pill">
                                Lihat
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M9 18l6-6-6-6"
                                        stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="md:col-span-3">
                    <div class="card p-8 text-center">
                        <div class="text-lg font-extrabold text-ink">Belum ada event</div>
                        <div class="text-sm text-slate-500 mt-1">
                            Coba ganti keyword atau pilih kategori lain ya.
                        </div>
                        <div class="mt-5">
                            <a href="{{ route('public.events.index') }}" class="btn-ghost">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div>
            {{ $events->links() }}
        </div>
    </div>
</x-guest-layout>
