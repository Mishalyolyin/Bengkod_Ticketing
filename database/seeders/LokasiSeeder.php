<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        Lokasi::updateOrCreate(['id' => 1], ['nama_lokasi' => 'Stadion Utama']);
        Lokasi::updateOrCreate(['id' => 2], ['nama_lokasi' => 'Galeri Seni Kota']);
        Lokasi::updateOrCreate(['id' => 3], ['nama_lokasi' => 'Taman Kota']);
    }
}
