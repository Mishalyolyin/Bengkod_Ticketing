<x-guest-layout>
    @php
        $minHarga = $event->tikets?->min('harga');
        $totalStok = $event->tikets?->sum('stok');
        $tanggal = optional($event->waktu)?->format('d M Y H:i');
    @endphp

    <div class="space-y-6">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="card p-4 border border-emerald-200 bg-emerald-50/60">
                <div class="text-emerald-700 font-semibold">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="card p-4 border border-rose-200 bg-rose-50/60">
                <div class="text-rose-700 font-semibold">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="card p-4 border border-rose-200 bg-rose-50/60">
                <div class="text-rose-700 font-semibold mb-2">Ada yang perlu dibenerin dulu:</div>
                <ul class="text-sm text-rose-700 list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header / Event info --}}
        <div class="card p-6">
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('public.events.index') }}" class="btn-ghost">← Back</a>

                <div class="flex items-center gap-2">
                    <span class="badge-soft">{{ $event->kategori?->nama ?? 'Tanpa Kategori' }}</span>

                    @if(!is_null($minHarga))
                        <span class="badge-soft">
                            Mulai Rp {{ number_format((int)$minHarga, 0, ',', '.') }}
                        </span>
                    @endif

                    <span class="badge-soft">
                        Sisa stok {{ (int)($totalStok ?? 0) }}
                    </span>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-12 gap-6">
                {{-- Poster --}}
                <div class="md:col-span-4">
                    <div class="card overflow-hidden">
                        <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
                            @if($event->gambar)
                                <img
                                    src="{{ asset('storage/'.$event->gambar) }}"
                                    class="w-full h-full object-cover"
                                    alt="poster {{ $event->judul }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-500">
                                    <div class="text-center">
                                        <div class="text-xs uppercase tracking-wide">No Poster</div>
                                        <div class="font-semibold">{{ \Illuminate\Support\Str::limit($event->judul, 28) }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="md:col-span-8">
                    <h1 class="text-2xl font-extrabold text-ink">{{ $event->judul }}</h1>
                    <div class="text-sm text-slate-500 mt-1">
                        {{ $event->lokasi }} • {{ $tanggal }}
                    </div>

                    <p class="mt-4 text-sm text-slate-600 leading-relaxed">
                        {{ $event->deskripsi }}
                    </p>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="card p-4">
                            <div class="text-xs text-slate-500">Kategori</div>
                            <div class="font-bold text-ink mt-1">{{ $event->kategori?->nama ?? '-' }}</div>
                        </div>

                        <div class="card p-4">
                            <div class="text-xs text-slate-500">Mulai dari</div>
                            <div class="font-bold text-ink mt-1">
                                @if(!is_null($minHarga))
                                    Rp {{ number_format((int)$minHarga, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="card p-4">
                            <div class="text-xs text-slate-500">Total stok</div>
                            <div class="font-bold text-ink mt-1">{{ (int)($totalStok ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ticket selection --}}
        <div class="card p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-extrabold text-ink">Pilih Tiket</h2>
                    <p class="text-sm text-slate-500">Masukin jumlah tiket, nanti totalnya auto ngitung ✨</p>
                </div>

                @guest
                    <a href="{{ route('login') }}" class="btn-ghost">Login dulu</a>
                @endguest
            </div>

            <form method="POST" action="{{ route('public.events.checkout', $event) }}" class="mt-4 space-y-3" id="checkoutForm">
                @csrf

                @forelse($event->tikets as $t)
                    @php
                        $stok = (int) $t->stok;
                        $disabled = $stok <= 0;
                        $oldQty = (int) data_get(old('qty', []), $t->id, 0);
                    @endphp

                    <div class="card p-4 border border-slate-100">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="font-extrabold text-ink uppercase tracking-wide">{{ $t->tipe }}</div>

                                    @if($disabled)
                                        <span class="badge bg-rose-50 text-rose-700 border border-rose-100">Habis</span>
                                    @else
                                        <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-100">Ready</span>
                                    @endif
                                </div>

                                <div class="text-sm text-slate-600 mt-1">
                                    Rp {{ number_format((int)$t->harga, 0, ',', '.') }}
                                    • <span class="text-slate-500">stok: {{ $stok }}</span>
                                </div>

                                <div class="text-xs text-slate-500 mt-2">
                                    Subtotal: <span class="font-semibold text-slate-700" data-subtotal-for="{{ $t->id }}">Rp 0</span>
                                </div>
                            </div>

                            {{-- Qty Control --}}
                            <div class="flex items-center gap-2 justify-end">
                                <button
                                    type="button"
                                    class="btn-ghost px-3 py-2"
                                    data-dec="{{ $t->id }}"
                                    {{ $disabled ? 'disabled' : '' }}
                                >−</button>

                                <input
                                    type="number"
                                    min="0"
                                    max="{{ $stok }}"
                                    inputmode="numeric"
                                    name="qty[{{ $t->id }}]"
                                    value="{{ $oldQty }}"
                                    class="input w-[110px] text-center"
                                    data-qty="{{ $t->id }}"
                                    data-price="{{ (int)$t->harga }}"
                                    data-max="{{ $stok }}"
                                    {{ $disabled ? 'disabled' : '' }}
                                >

                                <button
                                    type="button"
                                    class="btn-ghost px-3 py-2"
                                    data-inc="{{ $t->id }}"
                                    {{ $disabled ? 'disabled' : '' }}
                                >+</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card p-6 text-center">
                        <div class="font-extrabold text-ink">Belum ada tiket untuk event ini</div>
                        <div class="text-sm text-slate-500 mt-1">Admin belum nambahin tiketnya.</div>
                    </div>
                @endforelse

                {{-- Summary --}}
                <div class="card p-5 mt-4 border border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <div class="text-xs text-slate-500">Total</div>
                            <div class="text-xl font-extrabold text-ink" id="grandTotal">Rp 0</div>
                            <div class="text-xs text-slate-500 mt-1">
                                Item: <span class="font-semibold" id="grandItems">0</span>
                            </div>
                        </div>

                        <button class="btn-primary w-full sm:w-[260px] h-[46px]" id="checkoutBtn" disabled>
                            Checkout
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- JS total + stepper (tanpa build, langsung jalan) --}}
    <script>
        const rupiah = (n) => new Intl.NumberFormat('id-ID').format(n);

        const inputs = Array.from(document.querySelectorAll('[data-qty]'));
        const totalEl = document.getElementById('grandTotal');
        const itemsEl = document.getElementById('grandItems');
        const btn = document.getElementById('checkoutBtn');

        function recalc() {
            let total = 0;
            let items = 0;

            inputs.forEach(inp => {
                const id = inp.dataset.qty;
                const price = parseInt(inp.dataset.price || '0', 10);
                let qty = parseInt(inp.value || '0', 10);

                const max = parseInt(inp.dataset.max || '0', 10);
                if (qty < 0) qty = 0;
                if (qty > max) qty = max;
                inp.value = qty;

                const sub = qty * price;
                total += sub;
                items += qty;

                const subEl = document.querySelector(`[data-subtotal-for="${id}"]`);
                if (subEl) subEl.textContent = 'Rp ' + rupiah(sub);
            });

            totalEl.textContent = 'Rp ' + rupiah(total);
            itemsEl.textContent = items;

            const canCheckout = items > 0;
            btn.disabled = !canCheckout;

            btn.classList.toggle('opacity-60', !canCheckout);
            btn.classList.toggle('cursor-not-allowed', !canCheckout);
        }

        // +/- buttons
        document.addEventListener('click', (e) => {
            const inc = e.target.closest('[data-inc]');
            const dec = e.target.closest('[data-dec]');

            if (inc) {
                const id = inc.dataset.inc;
                const inp = document.querySelector(`[data-qty="${id}"]`);
                if (!inp || inp.disabled) return;
                inp.value = (parseInt(inp.value || '0', 10) + 1);
                recalc();
            }

            if (dec) {
                const id = dec.dataset.dec;
                const inp = document.querySelector(`[data-qty="${id}"]`);
                if (!inp || inp.disabled) return;
                inp.value = Math.max(0, parseInt(inp.value || '0', 10) - 1);
                recalc();
            }
        });

        // manual edit
        inputs.forEach(inp => inp.addEventListener('input', recalc));

        // init
        recalc();
    </script>
</x-guest-layout>
