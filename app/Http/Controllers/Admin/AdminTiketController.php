<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTiketRequest;
use App\Http\Requests\Admin\UpdateTiketRequest;
use App\Models\Event;
use App\Models\Tiket;

class AdminTiketController extends Controller
{
    private function ensureSameEvent(Event $event, Tiket $tiket): void
    {
        abort_unless($tiket->event_id === $event->id, 404);
    }

    public function index(Event $event)
    {
        $tikets = Tiket::where('event_id', $event->id)
            ->withSum('detailOrders', 'jumlah')
            ->orderBy('tipe')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tikets.index', compact('event', 'tikets'));
    }

    public function create(Event $event)
    {
        return view('admin.tikets.create', compact('event'));
    }

    public function store(StoreTiketRequest $request, Event $event)
    {
        $data = $request->validated();
        $data['event_id'] = $event->id;

        Tiket::create($data);

        return redirect()
            ->route('admin.events.tikets.index', $event)
            ->with('success', 'Tiket berhasil ditambahkan ✅');
    }

    public function edit(Event $event, Tiket $tiket)
    {
        $this->ensureSameEvent($event, $tiket);

        return view('admin.tikets.edit', compact('event', 'tiket'));
    }

    public function update(UpdateTiketRequest $request, Event $event, Tiket $tiket)
    {
        $this->ensureSameEvent($event, $tiket);

        $data = $request->validated();
        $tiket->update($data);

        return redirect()
            ->route('admin.events.tikets.index', $event)
            ->with('success', 'Tiket berhasil diupdate ✨');
    }

    public function destroy(Event $event, Tiket $tiket)
    {
        $this->ensureSameEvent($event, $tiket);

        // kalau sudah pernah dibeli, jangan dihapus
        if ($tiket->detailOrders()->exists()) {
            return back()->with('error', 'Tiket tidak bisa dihapus karena sudah ada transaksi.');
        }

        $tiket->delete();

        return redirect()
            ->route('admin.events.tikets.index', $event)
            ->with('success', 'Tiket berhasil dihapus 🗑️');
    }
}
