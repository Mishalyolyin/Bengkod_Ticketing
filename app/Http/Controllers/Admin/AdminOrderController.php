<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $orders = Order::with(['user', 'event.kategori'])
            ->withCount('details')
            ->when($q, function ($query) use ($q) {
                $query->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                })->orWhereHas('event', function ($e) use ($q) {
                    $e->where('judul', 'like', "%{$q}%")
                      ->orWhere('lokasi', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('order_date')
            ->paginate(15)
            ->withQueryString();

        $totalRevenue = Order::sum('total_price');

        return view('admin.orders.index', compact('orders', 'totalRevenue', 'q'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'event.kategori', 'details.tiket']);

        return view('admin.orders.show', compact('order'));
    }
}
