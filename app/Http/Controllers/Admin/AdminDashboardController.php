<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Order;
use App\Models\Tiket;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'kategori' => Kategori::count(),
            'event'    => Event::count(),
            'tiket'    => Tiket::count(),
            'order'    => Order::count(),
            'revenue'  => (int) Order::sum('total_price'),
        ];

        $latestOrders = Order::with(['user', 'event'])
            ->latest('order_date')
            ->take(6)
            ->get();

        $latestEvents = Event::with('kategori')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestOrders', 'latestEvents'));
    }
}
