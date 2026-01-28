<x-guest-layout>
    <section class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <div class="text-xs text-slate-500">
                    <a href="{{ route('public.events.index') }}" class="hover:text-ink transition">Events</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-700 font-medium">{{ $event->judul }}</span>
                </div>
                <h1 class="text-3xl font-extrabold text-ink mt-1">{{ $event->judul }}</h1>

                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                    <span class="badge-soft">{{ $event->kategori?->nama ?? '-' }}</span>
                    <span>📍 <span class="font-semibold text-slate-700">{{ $event->lokasi ?? '-' }}</span></span>
                    <span class="text-slate-300">•</span>
                    <span>🗓️ {{ optional($event->waktu)->format('D, d M Y') ?? '-' }} {{ optional($event->waktu)->format('H:i') ?? '' }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('public.events.index') }}" class="btn-ghost">← Kembali</a>
                @auth
                    <a href="{{ route('buyer.orders.index') }}" class="btn-outline">Riwayat Orders</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Login buat Checkout</a>
                @endauth
            </div>
        </div>

        {{-- Poster + Desc --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="card overflow-hidden lg:col-span-1">
                <div class="h-64 bg-slate-100">
                    @if($event->gambar)
                        <img src="{{ asset('storage/'.$event->gambar) }}" class="w-full h-64 object-cover" alt="poster">
                    @endif
                </div>
                <div class="p-5">
                    <div class="text-xs text-slate-500">Lokasi</div>
                    <div class="font-extrabold text-ink mt-1">📍 {{ $event->lokasi ?? '-' }}</div>

                    <div class="mt-4 text-xs text-slate-500">Waktu</div>
                    <div class="font-extrabold text-ink mt-1">
                        {{ optional($event->waktu)->format('D, d M Y') ?? '-' }}
                    </div>
                    <div class="text-sm text-slate-600">
                        {{ optional($event->waktu)->format('H:i') ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="card p-6 lg:col-span-2">
                <div class="font-extrabold text-ink text-lg">Tentang Event</div>
                <p class="text-slate-600 mt-2 leading-relaxed">
                    {{ $event->deskripsi }}
                </p>

                <div class="mt-6 border-t border-white/60 pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-extrabold text-ink text-lg">Pilih Tiket</div>
                            <div class="text-sm text-slate-600">Isi quantity. Checkout butuh login.</div>
                        </div>
                        <span class="badge-soft">Stok live</span>
                    </div>

                    @if($errors->has('checkout'))
                        <div class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                            {{ $errors->first('checkout') }}
                        </div>
                    @endif

                    <form action="{{ route('public.events.checkout', $event) }}" method="POST" class="mt-4 space-y-3">
                        @csrf

                        @foreach($event->tikets as $t)
                            <div class="p-4 rounded-2xl border border-slate-200 bg-white/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <div class="font-extrabold text-ink">
                                        {{ strtoupper($t->tipe ?? $t->nama ?? 'TIKET') }}
                                    </div>
                                    <div class="text-sm text-slate-600 mt-1">
                                        Rp {{ number_format((int)$t->harga, 0, ',', '.') }}
                                        <span class="text-slate-300">•</span>
                                        Stok: <span class="font-semibold">{{ (int)$t->stok }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-slate-600">Qty</label>
                                    <input
                                        type="number"
                                        name="qty[{{ $t->id }}]"
                                        min="0"
                                        max="{{ (int)$t->stok }}"
                                        value="{{ old('qty.'.$t->id, 0) }}"
                                        class="input w-24"
                                    />
                                </div>
                            </div>
                        @endforeach

                        <div class="pt-2 flex flex-col sm:flex-row gap-2">
                            @auth
                                <button type="submit" class="btn-primary w-full sm:w-auto">
                                    🧾 Checkout Sekarang
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-primary w-full sm:w-auto text-center">
                                    Login dulu buat Checkout
                                </a>
                                <a href="{{ route('register') }}" class="btn-ghost w-full sm:w-auto text-center">
                                    Belum punya akun? Register
                                </a>
                            @endauth
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
