<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminKategoriController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $kategoris = Kategori::query()
            ->withCount('events')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $total = Kategori::count();

        return view('admin.kategori.index', compact('kategoris', 'q', 'total'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:80',
                Rule::unique('kategoris', 'nama'),
            ],
        ]);

        Kategori::create($data);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan ✅');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:80',
                Rule::unique('kategoris', 'nama')->ignore($kategori->id),
            ],
        ]);

        $kategori->update($data);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate ✨');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->events()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh event.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
