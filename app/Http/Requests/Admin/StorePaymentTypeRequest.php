<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // udah diproteksi middleware admin di routes
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:payment_types,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tipe pembayaran wajib diisi.',
            'name.unique' => 'Nama tipe pembayaran sudah ada.',
        ];
    }
}
