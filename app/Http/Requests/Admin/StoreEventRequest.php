<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->role ?? null) === 'admin';
    }

    public function rules(): array
    {
        return [
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul'       => ['required', 'string', 'max:255'],
            'deskripsi'   => ['required', 'string'],
            'lokasi'      => ['required', 'string', 'max:255', 'exists:lokasis,nama_lokasi'], // ✅ FIX
            'waktu'       => ['required', 'date'],
            'gambar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
