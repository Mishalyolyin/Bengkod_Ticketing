<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 leading-tight">Detail Pesanan</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Ringkasan transaksi untuk event <span class="font-semibold text-ink">{{ $order->event?->judul ?? 'Event' }}</span>
                </p>
            </div>
            <a href="{{ route('buyer.orders.index') }}" class="btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Toast --}}
            @if (session('success'))
                <div class="card p-5 border border-emerald-200 bg-emerald-50/70">
                    <div class="font-extrabold text-emerald-800">Berhasil ✅</div>
                    <div class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</div>
                </div>
            @endif

            {{-- Info (khusus status belum paid) --}}
            @if ($errors->has('status'))
                <div class="card p-5 border border-indigo-200 bg-indigo-50/70">
                    <div class="font-extrabold text-indigo-800">Info</div>
                    <div class="text-sm text-indigo-700 mt-0.5">{{ $errors->first('status') }}</div>
                </div>
            @endif

            {{-- Error beneran (selain status) --}}
            @if ($errors->any() && !$errors->has('status'))
                <div class="card p-5 border border-red-200 bg-red-50/70">
                    <div class="font-extrabold text-red-800">Ada yang perlu dibenerin 😭</div>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Summary --}}
            <div class="card p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Tanggal</div>
                        <div class="font-extrabold text-ink">
                            {{ optional($order->order_date)->format('d M Y H:i') ?? $order->created_at?->format('d M Y H:i') }}
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Total Bayar</div>
                        <div class="font-extrabold text-ink">
                            Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Jumlah Tiket</div>
                        <div class="font-extrabold text-ink">
                            {{ $order->detailOrders->sum('jumlah') }}
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Metode</div>
                        <div class="font-extrabold text-ink">
                            {{ $order->paymentType?->name ?? 'Belum dipilih' }}
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border border-white/60 bg-white/60">
                        <div class="text-xs text-slate-500">Status</div>
                        <div class="font-extrabold text-ink">
                            {{ strtoupper($order->status ?? 'pending') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metode pembayaran --}}
            <div class="card p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-ink">Metode Pembayaran</h3>
                        <p class="text-sm text-slate-500 mt-0.5">
                            Pilih dulu metodenya, baru lanjut bayar biar “resmi” 😄
                        </p>
                    </div>

                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        Saat ini: <span class="ml-1 text-slate-900">{{ $order->paymentType?->name ?? 'Belum dipilih' }}</span>
                    </span>
                </div>

                <div class="mt-4 flex flex-col md:flex-row gap-3 md:items-end">
                    {{-- Update metode --}}
                    <form method="POST" action="{{ route('buyer.orders.payment-type', $order) }}"
                          class="flex-1 flex flex-col md:flex-row gap-3 md:items-end">
                        @csrf
                        @method('PATCH')

                        <div class="flex-1">
                            <label class="text-xs font-semibold text-slate-600">Pilih Metode</label>
                            <select name="payment_type_id"
                                    class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-400 focus:ring-indigo-200"
                                    @disabled(($order->status ?? 'pending') === 'paid')>
                                <option value="">-- pilih metode --</option>
                                @foreach($paymentTypes as $pt)
                                    <option value="{{ $pt->id }}" @selected(old('payment_type_id', $order->payment_type_id) == $pt->id)>
                                        {{ $pt->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn-primary md:px-6"
                                @disabled(($order->status ?? 'pending') === 'paid')>
                            Update Metode
                        </button>
                    </form>

                    {{-- Bayar sekarang --}}
                    <form method="POST" action="{{ route('buyer.orders.pay', $order) }}">
                        @csrf
                        <button type="submit"
                                class="btn-primary md:px-6 w-full"
                                @disabled(($order->status ?? 'pending') === 'paid' || !$order->payment_type_id)>
                            Bayar Sekarang ✅
                        </button>

                        @if(!$order->payment_type_id && ($order->status ?? 'pending') !== 'paid')
                            <div class="text-xs text-red-600 mt-2">Pilih metode dulu biar tombolnya aktif.</div>
                        @endif
                    </form>
                </div>

                @if(($order->status ?? 'pending') === 'paid')
                    <div class="mt-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                        Order ini sudah dibayar pada <b>{{ optional($order->paid_at)->format('d M Y H:i') }}</b>.
                        <a class="underline font-semibold" href="{{ route('buyer.orders.success', $order) }}">Lihat halaman sukses</a>.
                    </div>
                @endif
            </div>

            {{-- Rincian tiket --}}
            <div class="card p-6">
                <h3 class="text-lg font-extrabold text-ink mb-4">Rincian Tiket</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b">
                                <th class="py-2">Tiket</th>
                                <th class="py-2">Harga</th>
                                <th class="py-2">Qty</th>
                                <th class="py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->detailOrders as $d)
                                <tr class="border-b last:border-0">
                                    <td class="py-3 font-semibold text-ink">
                                        {{ strtoupper($d->tiket?->tipe ?? 'TIKET') }}
                                    </td>
                                    <td class="py-3 text-slate-600">
                                        Rp {{ number_format((int)($d->tiket?->harga ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-slate-600">{{ (int)$d->jumlah }}</td>
                                    <td class="py-3 text-right font-extrabold text-ink">
                                        Rp {{ number_format((int)$d->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-4 text-right font-semibold text-slate-600">Total</td>
                                <td class="pt-4 text-right font-extrabold text-ink">
                                    Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-5 flex items-center justify-end gap-2">
                    <a href="{{ route('public.events.show', $order->event) }}" class="btn-ghost">Lihat Event</a>
                    <a href="{{ route('buyer.orders.index') }}" class="btn-primary">Kembali ke Riwayat</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
