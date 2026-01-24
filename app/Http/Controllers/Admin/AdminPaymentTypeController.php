<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentTypeRequest;
use App\Http\Requests\Admin\UpdatePaymentTypeRequest;
use App\Models\PaymentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentTypeController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $paymentTypes = PaymentType::query()
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payment-types.index', compact('paymentTypes', 'q'));
    }

    public function create(): View
    {
        return view('admin.payment-types.create');
    }

    public function store(StorePaymentTypeRequest $request): RedirectResponse
    {
        PaymentType::create([
            'name' => $request->validated()['name'],
        ]);

        return redirect()
            ->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil ditambahkan.');
    }

    public function edit(PaymentType $payment_type): View
    {
        return view('admin.payment-types.edit', [
            'paymentType' => $payment_type,
        ]);
    }

    public function update(UpdatePaymentTypeRequest $request, PaymentType $payment_type): RedirectResponse
    {
        $payment_type->update([
            'name' => $request->validated()['name'],
        ]);

        return redirect()
            ->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentType $payment_type): RedirectResponse
    {
        $payment_type->delete();

        return redirect()
            ->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil dihapus.');
    }
}
