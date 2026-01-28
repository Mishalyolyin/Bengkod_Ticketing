<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Lokasi; // ✅ tambah
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

        $allEvents = Event::orderByDesc('waktu')->get(['id', 'judul', 'waktu']);

        return view('admin.events.index', compact('events', 'kategoris', 'allEvents', 'q', 'kategoriId'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);
        $lokasis   = Lokasi::orderBy('nama_lokasi')->get(['id','nama_lokasi']); // ✅ tambah
        return view('admin.events.create', compact('kategoris', 'lokasis'));
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

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
        $lokasis   = Lokasi::orderBy('nama_lokasi')->get(['id','nama_lokasi']); // ✅ tambah
        return view('admin.events.edit', compact('event', 'kategoris', 'lokasis'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
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
        $hasOrders = $event->orders()->exists();

        $hasPurchasedTickets = DB::table('detail_orders')
            ->join('tikets', 'detail_orders.tiket_id', '=', 'tikets.id')
            ->where('tikets.event_id', $event->id)
            ->exists();

        if ($hasOrders || $hasPurchasedTickets) {
            return back()->with('error', 'Event tidak bisa dihapus karena sudah memiliki transaksi / tiketnya sudah pernah dibeli.');
        }

        $event->tikets()->delete();

        if ($event->gambar && Storage::disk('public')->exists($event->gambar)) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus 🗑️');
    }
}
