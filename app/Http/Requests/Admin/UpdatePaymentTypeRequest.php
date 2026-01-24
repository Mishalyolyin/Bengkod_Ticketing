<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentTypeId = $this->route('payment_type')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('payment_types', 'name')->ignore($paymentTypeId),
            ],
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
