<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminLokasiController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $lokasis = Lokasi::query()
            ->when($q !== '', fn($qq) => $qq->where('nama_lokasi', 'like', "%{$q}%"))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.lokasi.index', compact('lokasis', 'q'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lokasi' => ['required','string','max:255', Rule::unique('lokasis','nama_lokasi')],
        ]);

        Lokasi::create($data);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan ✅');
    }

    public function edit(Lokasi $lokasi)
    {
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $data = $request->validate([
            'nama_lokasi' => ['required','string','max:255', Rule::unique('lokasis','nama_lokasi')->ignore($lokasi->id)],
        ]);

        // karena Event nyimpan lokasi sebagai string, jangan rename kalau sudah dipakai
        $dipakai = Event::where('lokasi', $lokasi->nama_lokasi)->exists();
        if ($dipakai && $data['nama_lokasi'] !== $lokasi->nama_lokasi) {
            return back()->with('error', 'Lokasi ini sudah dipakai Event, jadi namanya tidak boleh diganti 😭');
        }

        $lokasi->update($data);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil diupdate ✨');
    }

    public function destroy(Lokasi $lokasi)
    {
        $dipakai = Event::where('lokasi', $lokasi->nama_lokasi)->exists();
        if ($dipakai) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena masih dipakai Event.');
        }

        $lokasi->update(['aktif' => 'N']);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus 🗑️');
    }
}
