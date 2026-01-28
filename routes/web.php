<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;

use App\Http\Controllers\ProfileController;

// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\AdminTiketController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentTypeController;
use App\Http\Controllers\Admin\AdminLokasiController; // ✅ NEW

// Public / Buyer
use App\Http\Controllers\Public\EventController as PublicEventController;
use App\Http\Controllers\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $events = Event::with(['kategori', 'tikets'])
        ->orderBy('waktu', 'asc')
        ->take(9)
        ->get();

    return view('public.home', compact('events'));
})->name('home');

/*
|--------------------------------------------------------------------------
| Public Events
|--------------------------------------------------------------------------
*/
Route::name('public.')->group(function () {
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

    Route::post('/events/{event}/checkout', [PublicEventController::class, 'checkout'])
        ->middleware('auth')
        ->name('events.checkout');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Buyer Dashboard (auth + verified)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [BuyerDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Buyer Orders
    Route::name('buyer.')->group(function () {
        Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('orders.show');

        // ganti metode pembayaran
        Route::patch('/orders/{order}/payment-type', [BuyerOrderController::class, 'updatePaymentType'])
            ->name('orders.payment-type');

        // Opsi A: Bayar sekarang => status paid
        Route::post('/orders/{order}/pay', [BuyerOrderController::class, 'pay'])
            ->name('orders.pay');

        // halaman sukses
        Route::get('/orders/{order}/success', [BuyerOrderController::class, 'success'])
            ->name('orders.success');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', AdminEventController::class)->except(['show']);
        Route::resource('kategori', AdminKategoriController::class)->except(['show']);

        // ✅ NEW: Management Lokasi CRUD
        Route::resource('lokasi', AdminLokasiController::class)->except(['show']);

        // Admin transaksi
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

        // Payment Types CRUD
        Route::resource('payment-types', AdminPaymentTypeController::class)->except(['show']);

        // Nested Tikets per Event
        Route::prefix('events/{event}/tikets')
            ->scopeBindings()
            ->name('events.tikets.')
            ->group(function () {
                Route::get('/', [AdminTiketController::class, 'index'])->name('index');
                Route::get('/create', [AdminTiketController::class, 'create'])->name('create');
                Route::post('/', [AdminTiketController::class, 'store'])->name('store');

                Route::get('/{tiket}/edit', [AdminTiketController::class, 'edit'])->name('edit');
                Route::put('/{tiket}', [AdminTiketController::class, 'update'])->name('update');
                Route::delete('/{tiket}', [AdminTiketController::class, 'destroy'])->name('destroy');
            });
    });
