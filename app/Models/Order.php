<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'order_date',
        'total_price',
        'payment_type_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'order_date'  => 'datetime',
        'paid_at'     => 'datetime',
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

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function detailOrders(): HasMany
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }

    // backward-compat
    public function details(): HasMany
    {
        return $this->detailOrders();
    }
}
