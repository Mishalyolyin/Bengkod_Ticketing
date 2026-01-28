<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <div class="text-xs text-slate-500">
                    <a href="{{ route('buyer.orders.index') }}" class="hover:text-slate-700 transition">Orders</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-700 font-medium">Detail #{{ $order->id }}</span>
                </div>
                <h2 class="font-extrabold text-xl text-gray-800 leading-tight">
                    Detail Order
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Event: <span class="font-semibold text-slate-700">{{ $order->event?->judul ?? '-' }}</span>
                    <span class="text-slate-300">•</span>
                    Lokasi: <span class="font-semibold text-slate-700">📍 {{ $order->event?->lokasi ?? '-' }}</span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('public.events.show', $order->event) }}" class="btn-outline">Lihat Event</a>
                <a href="{{ route('buyer.orders.index') }}" class="btn-ghost">← Kembali</a>
            </div>
        </div>
    </x-slot>

    @php
        $status = strtolower($order->status ?? 'pending');
        $isPaid = $status === 'paid';
        $invoice = 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        $paidAt  = $order->paid_at ?? null;
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 space-y-4">

            @if(session('success'))
                <div class="card p-4 border border-emerald-200 bg-emerald-50/60 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="card p-4 border border-rose-200 bg-rose-50/60 text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="card p-5">
                    <div class="text-xs text-slate-500">Invoice</div>
                    <div class="mt-1 text-lg font-extrabold text-ink">{{ $invoice }}</div>
                    <div class="text-xs text-slate-500 mt-1">Order ID: #{{ $order->id }}</div>
                </div>

                <div class="card p-5">
                    <div class="text-xs text-slate-500">Status</div>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $isPaid ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                            {{ strtoupper($order->status ?? 'PENDING') }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mt-2">
                        @if($isPaid)
                            Dibayar: <span class="font-semibold text-slate-700">{{ optional($paidAt)->format('d M Y H:i') }}</span>
                        @else
                            Belum dibayar. Pilih metode dulu ya.
                        @endif
                    </div>
                </div>

                <div class="card p-5">
                    <div class="text-xs text-slate-500">Lokasi Event</div>
                    <div class="mt-1 font-extrabold text-ink">📍 {{ $order->event?->lokasi ?? '-' }}</div>
                    <div class="text-xs text-slate-500 mt-1">
                        {{ optional($order->event?->waktu)->format('d M Y H:i') ?? '-' }}
                    </div>
                </div>

                <div class="card p-5">
                    <div class="text-xs text-slate-500">Total</div>
                    <div class="mt-1 text-lg font-extrabold text-ink">
                        Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">
                        Metode: <span class="font-semibold text-slate-700">{{ $order->paymentType?->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Box (kalau belum paid) --}}
            @if(!$isPaid)
                <div class="card p-6">
                    <div class="font-extrabold text-ink">Metode Pembayaran</div>
                    <div class="text-sm text-slate-600 mt-1">Pilih metode, lalu klik bayar (simulasi).</div>

                    <form method="POST" action="{{ route('buyer.orders.payment-type', $order) }}" class="mt-4 flex flex-col sm:flex-row gap-2">
                        @csrf
                        @method('PATCH')

                        <select name="payment_type_id" class="input sm:w-80">
                            <option value="">-- pilih metode --</option>
                            @foreach($paymentTypes as $pt)
                                <option value="{{ $pt->id }}" @selected((int)($order->payment_type_id ?? 0) === (int)$pt->id)>
                                    {{ $pt->name }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn-outline" type="submit">Simpan Metode</button>
                    </form>

                    <form method="POST" action="{{ route('buyer.orders.pay', $order) }}" class="mt-3">
                        @csrf
                        <button class="btn-primary w-full sm:w-auto" type="submit">
                            💳 Bayar Sekarang
                        </button>
                    </form>
                </div>
            @else
                <div class="card p-6 border border-emerald-200 bg-emerald-50/50">
                    <div class="font-extrabold text-emerald-800">Order sudah dibayar ✅</div>
                    <div class="text-sm text-emerald-800/80 mt-1">
                        Kamu bisa buka halaman sukses buat invoice ringkas.
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('buyer.orders.success', $order) }}" class="btn-primary">Lihat Halaman Sukses</a>
                    </div>
                </div>
            @endif

            {{-- Rincian Tiket --}}
            <div class="card overflow-hidden">
                <div class="px-4 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-gray-900">Rincian Tiket</div>
                        <div class="text-sm text-gray-500">Isi pembelian kamu.</div>
                    </div>
                    <span class="badge-soft">Items: {{ (int)$order->detailOrders->sum('jumlah') }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                                <th class="py-3 px-4">Tiket</th>
                                <th class="py-3 px-4">Harga</th>
                                <th class="py-3 px-4">Jumlah</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($order->detailOrders as $d)
                                <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ strtoupper($d->tiket?->tipe ?? 'TIKET') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">
                                        Rp {{ number_format((int)($d->tiket?->harga ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">{{ (int)$d->jumlah }}</td>
                                    <td class="py-3 px-4 text-right font-extrabold text-gray-900">
                                        Rp {{ number_format((int)$d->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="border-t border-gray-100">
                                <td colspan="3" class="py-4 px-4 text-right font-semibold text-gray-600">Total</td>
                                <td class="py-4 px-4 text-right font-extrabold text-gray-900">
                                    Rp {{ number_format((int)$order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
