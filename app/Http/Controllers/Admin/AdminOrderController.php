<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Order::with(['user', 'event.kategori', 'paymentType'])
            ->withCount('details');

        // Opsi A: Admin cuma lihat order yang sudah "success/paid"
        if (Schema::hasColumn('orders', 'status')) {
            $query->where('status', 'paid');
        }

        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                })
                ->orWhereHas('event', function ($e) use ($q) {
                    $e->where('judul', 'like', "%{$q}%")
                      ->orWhere('lokasi', 'like', "%{$q}%");
                })
                ->orWhereHas('paymentType', function ($p) use ($q) {
                    $p->where('name', 'like', "%{$q}%");
                })
                ->orWhere('id', $q);
            });
        }

        // Urutin yang paling baru dibayar (kalau ada), kalau nggak fallback ke order_date
        if (Schema::hasColumn('orders', 'paid_at')) {
            $query->orderByDesc('paid_at');
        } else {
            $query->orderByDesc('order_date');
        }

        $orders = $query->paginate(15)->withQueryString();

        // revenue: ikut filter paid biar konsisten sama list
        $revenueQuery = Order::query();
        if (Schema::hasColumn('orders', 'status')) {
            $revenueQuery->where('status', 'paid');
        }
        $totalRevenue = $revenueQuery->sum('total_price');

        return view('admin.orders.index', compact('orders', 'totalRevenue', 'q'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'event.kategori', 'details.tiket', 'paymentType']);

        // Kalau Opsi A dipakai dan ada kolom status, admin hanya boleh lihat yang paid
        if (Schema::hasColumn('orders', 'status') && ($order->status ?? null) !== 'paid') {
            abort(404);
        }

        return view('admin.orders.show', compact('order'));
    }
}
