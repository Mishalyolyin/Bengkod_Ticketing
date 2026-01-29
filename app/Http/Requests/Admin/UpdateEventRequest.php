<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'deskripsi'   => ['nullable', 'string'],

            'lokasi_id'   => ['required', 'exists:lokasis,id'],
            'kota'        => ['required', 'string', 'max:255'],

            'waktu'       => ['required', 'date'],
            'gambar'      => ['nullable', 'image', 'max:2048'],
        ];
    }
}
