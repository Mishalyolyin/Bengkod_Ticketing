<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'lokasi',
        'kota', 
        'waktu',
        'gambar',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class, 'event_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'event_id');
    }
}
