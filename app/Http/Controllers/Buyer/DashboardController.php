<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin nyasar ke /dashboard? lempar balik ke admin panel
        if (($user->role ?? null) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $base = Order::query()->where('user_id', $user->id);

        $ordersCount = (int) (clone $base)->count();
        $totalSpent  = (int) (clone $base)->sum('total_price');

        $ticketsBought = (int) DetailOrder::query()
            ->whereHas('order', fn ($q) => $q->where('user_id', $user->id))
            ->sum('jumlah');

        $recentOrders = (clone $base)
            ->with(['event.kategori', 'details.tiket'])
            ->latest('order_date')
            ->take(5)
            ->get();

        $upcomingOrders = (clone $base)
            ->with(['event.kategori'])
            ->whereHas('event', fn ($q) => $q->whereNotNull('waktu')->where('waktu', '>=', now()))
            ->latest('order_date')
            ->take(5)
            ->get();

        return view('buyer.dashboard', compact(
            'ordersCount',
            'totalSpent',
            'ticketsBought',
            'recentOrders',
            'upcomingOrders'
        ));
    }
}
