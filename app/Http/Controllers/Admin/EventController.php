<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategoriId = $request->query('kategori');

        $events = Event::with('kategori')
            ->withCount(['tikets', 'orders'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('lokasi', 'like', "%{$q}%");
                });
            })
            ->when($kategoriId, fn ($query) => $query->where('kategori_id', $kategoriId))
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);

        // buat dropdown tambah tiket: ambil semua event (bukan cuma yang paginasi)
        $allEvents = Event::orderByDesc('waktu')->get(['id', 'judul', 'waktu']);

        return view('admin.events.index', compact('events', 'kategoris', 'allEvents', 'q', 'kategoriId'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);
        return view('admin.events.create', compact('kategoris'));
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        // kolom FK di migration: user_id
        $data['user_id'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('events', 'public');
        }

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan ✅');
    }

    public function edit(Event $event)
    {
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);
        return view('admin.events.edit', compact('event', 'kategoris'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            // hapus poster lama kalau ada
            if ($event->gambar && Storage::disk('public')->exists($event->gambar)) {
                Storage::disk('public')->delete($event->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('events', 'public');
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil diupdate ✨');
    }

    public function destroy(Event $event)
    {
        /**
         * Safe delete rules:
         * 1) Kalau event sudah punya order, gak boleh dihapus.
         * 2) Kalau ada tiket event ini yang sudah pernah kebeli (ada detail_orders), gak boleh hapus juga.
         *    (lebih ketat dan lebih aman buat demo + data integrity)
         */
        $hasOrders = $event->orders()->exists();

        $hasPurchasedTickets = DB::table('detail_orders')
            ->join('tikets', 'detail_orders.tiket_id', '=', 'tikets.id')
            ->where('tikets.event_id', $event->id)
            ->exists();

        if ($hasOrders || $hasPurchasedTickets) {
            return back()->with('error', 'Event tidak bisa dihapus karena sudah memiliki transaksi / tiketnya sudah pernah dibeli.');
        }

        // hapus tiket event (kalau belum ada transaksi, harusnya aman)
        $event->tikets()->delete();

        // hapus poster
        if ($event->gambar && Storage::disk('public')->exists($event->gambar)) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus 🗑️');
    }
}
