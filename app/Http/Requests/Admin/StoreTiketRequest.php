<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->role ?? null) === 'admin';
    }

    public function rules(): array
    {
        return [
            'tipe'  => ['required', 'in:premium,reguler'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok'  => ['required', 'integer', 'min:0'],
        ];
    }
}
