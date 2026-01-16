<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['user_id', 'event_id', 'order_date', 'total_price'];

    protected $casts = [
        'order_date'  => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // ✅ Canonical: dipakai buyer views ($order->detailOrders)
    public function detailOrders(): HasMany
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }

    // ✅ Backward-compat: biar AdminOrderController lama yang pakai details() tetap aman
    public function details(): HasMany
    {
        return $this->detailOrders();
    }
}
    