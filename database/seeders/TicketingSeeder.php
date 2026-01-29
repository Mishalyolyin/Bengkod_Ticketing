<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TicketingSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@tixora.test'],
            [
                'name' => 'Admin TIXORA',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'no_telp' => '081234567890',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Buyer dummy
        User::firstOrCreate(
            ['email' => 'buyer@tixora.test'],
            [
                'name' => 'Buyer Demo',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'no_telp' => '081111111111',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Kategori
        $kategoriNames = ['Music', 'Seminar', 'Sport', 'Workshop', 'Festival'];
        $kategoris = collect($kategoriNames)->map(fn($n) => Kategori::firstOrCreate(['nama' => $n]));

        // Event dummy
        $eventsData = [
            ['judul' => 'Night City Festival', 'kategori' => 'Festival', 'lokasi' => 'Semarang', 'hari' => 3],
            ['judul' => 'Seminar Digital Future', 'kategori' => 'Seminar', 'lokasi' => 'Semarang', 'hari' => 5],
            ['judul' => 'Indie Music Night', 'kategori' => 'Music', 'lokasi' => 'Semarang', 'hari' => 7],
            ['judul' => 'Workshop UI/UX Sprint', 'kategori' => 'Workshop', 'lokasi' => 'Semarang', 'hari' => 10],
            ['judul' => 'City Fun Run 5K', 'kategori' => 'Sport', 'lokasi' => 'Semarang', 'hari' => 12],
            ['judul' => 'Campus Fest', 'kategori' => 'Festival', 'lokasi' => 'Semarang', 'hari' => 15],
        ];

        foreach ($eventsData as $e) {
            $kat = $kategoris->firstWhere('nama', $e['kategori']);

            $event = Event::create([
                'user_id' => $admin->id,
                'kategori_id' => $kat->id,
                'judul' => $e['judul'],
                'deskripsi' => 'Event resmi dengan tiket terbatas. Amankan tiketmu sebelum kehabisan.',
                'lokasi' => $e['lokasi'],
                'kota' => 'Semarang',
                'waktu' => Carbon::now()->addDays($e['hari'])->setTime(19, 0),
                'gambar' => null,
            ]);

            // Tiket: reguler + premium
            Tiket::create([
                'event_id' => $event->id,
                'tipe' => 'reguler',
                'harga' => 50000,
                'stok' => 120,
            ]);

            Tiket::create([
                'event_id' => $event->id,
                'tipe' => 'premium',
                'harga' => 150000,
                'stok' => 60,
            ]);
        }
    }
}
