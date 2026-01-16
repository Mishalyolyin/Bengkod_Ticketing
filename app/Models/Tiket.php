<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tiket extends Model
{
    protected $fillable = ['event_id', 'tipe', 'harga', 'stok'];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function detailOrders(): HasMany
    {
        return $this->hasMany(DetailOrder::class, 'tiket_id');
    }
    
}
