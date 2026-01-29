<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Order;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategoriId = $request->query('kategori');

        // ✅ FIX: eager load tikets biar view bisa nampilin min price + stok tanpa N+1
        $events = Event::with(['kategori', 'tikets'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('lokasi', 'like', "%{$q}%");
                });
            })
            ->when($kategoriId, fn ($query) => $query->where('kategori_id', $kategoriId))
            ->orderBy('waktu')
            ->paginate(9)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama')->get();

        return view('public.events.index', compact('events', 'kategoris', 'q', 'kategoriId'));
    }

    public function show(Event $event)
    {
        $event->load(['kategori', 'tikets' => fn ($q) => $q->orderBy('harga')]);
        return view('public.events.show', compact('event'));
    }

    public function checkout(Request $request, Event $event)
    {
        // Support 2 format:
        // 1) tiket_id[] + jumlah[]
        // 2) qty[tiket_id] = jumlah
        $request->validate([
            'tiket_id' => ['nullable', 'array'],
            'tiket_id.*' => ['integer'],
            'jumlah' => ['nullable', 'array'],
            'jumlah.*' => ['integer', 'min:0'],

            'qty' => ['nullable', 'array'],
            'qty.*' => ['integer', 'min:0'],
        ]);

        $items = [];

        if (is_array($request->input('qty'))) {
            foreach ($request->input('qty') as $tiketId => $qty) {
                $qty = (int) $qty;
                if ($qty > 0) $items[] = ['tiket_id' => (int) $tiketId, 'jumlah' => $qty];
            }
        } else {
            $ids = (array) $request->input('tiket_id', []);
            $jm  = (array) $request->input('jumlah', []);
            foreach ($ids as $i => $tiketId) {
                $qty = (int) ($jm[$i] ?? 0);
                if ($qty > 0) $items[] = ['tiket_id' => (int) $tiketId, 'jumlah' => $qty];
            }
        }

        if (count($items) === 0) {
            return back()->withErrors(['checkout' => 'Pilih minimal 1 tiket dulu ya.'])->withInput();
        }

        return DB::transaction(function () use ($request, $event, $items) {
            // Lock tiket biar stok aman
            $tiketIds = collect($items)->pluck('tiket_id')->unique()->values();

            $tikets = Tiket::query()
                ->where('event_id', $event->id)
                ->whereIn('id', $tiketIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;

            foreach ($items as $it) {
                $t = $tikets->get($it['tiket_id']);

                if (!$t) {
                    return back()->withErrors(['checkout' => 'Ada tiket yang tidak valid untuk event ini.'])->withInput();
                }

                if ($t->stok < $it['jumlah']) {
                    return back()->withErrors(['checkout' => "Stok tiket '{$t->nama}' tidak cukup."])->withInput();
                }

                $total += ((int) $t->harga) * ((int) $it['jumlah']);
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'event_id' => $event->id,
                'order_date' => now(),
                'total_price' => $total,
            ]);

            foreach ($items as $it) {
                $t = $tikets[$it['tiket_id']];

                DetailOrder::create([
                    'order_id' => $order->id,
                    'tiket_id' => $t->id,
                    'jumlah' => (int) $it['jumlah'],
                    'subtotal' => ((int) $t->harga) * ((int) $it['jumlah']),
                ]);

                $t->decrement('stok', (int) $it['jumlah']);
            }

            return redirect()
                ->route('buyer.orders.show', $order)
                ->with('success', 'Checkout berhasil. Pesanan Anda telah tercatat.');
        });
    }
}
