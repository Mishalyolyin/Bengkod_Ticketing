<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['event', 'paymentType'])
            ->where('user_id', auth()->id())
            ->orderByDesc('order_date')
            ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['event', 'detailOrders.tiket', 'paymentType']);
        $paymentTypes = PaymentType::orderBy('name')->get();

        return view('buyer.orders.show', compact('order', 'paymentTypes'));
    }

    public function updatePaymentType(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (($order->status ?? 'pending') === 'paid') {
            return back()->withErrors(['payment_type_id' => 'Order sudah dibayar. Metode tidak bisa diubah.']);
        }

        $data = $request->validate([
            'payment_type_id' => ['required', 'exists:payment_types,id'],
        ]);

        // pakai assign manual biar aman
        $order->payment_type_id = (int) $data['payment_type_id'];
        $order->save();

        return back()->with('success', 'Metode pembayaran berhasil diupdate ✅');
    }

    public function pay(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (($order->status ?? 'pending') === 'paid') {
            return redirect()->route('buyer.orders.success', $order);
        }

        if (!$order->payment_type_id) {
            return back()->withErrors(['payment_type_id' => 'Pilih metode pembayaran dulu ya.']);
        }

        // ini yang bikin "Bayar Sekarang" beneran ngubah status
        $order->status = 'paid';
        $order->paid_at = now();
        $order->save();

        return redirect()
            ->route('buyer.orders.success', $order)
            ->with('success', 'Pembayaran berhasil ✅ (Simulasi)');
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (($order->status ?? 'pending') !== 'paid') {
            return redirect()
                ->route('buyer.orders.show', $order)
                ->withErrors(['status' => 'Order ini belum dibayar.']);
        }

        $order->load(['event', 'detailOrders.tiket', 'paymentType']);

        return view('buyer.orders.success', compact('order'));
    }
}
