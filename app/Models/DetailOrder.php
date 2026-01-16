<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailOrder extends Model
{
    protected $fillable = ['order_id', 'tiket_id', 'jumlah', 'subtotal'];

    protected $casts = [
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'tiket_id');
    }
}
