<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('kategori_id')
                ->constrained('kategoris')
                ->cascadeOnUpdate()
                ->restrictOnDelete(); // kategori ga boleh dihapus kalau masih dipakai event

            $table->string('judul');
            $table->text('deskripsi')->nullable();

            // ✅ lokasi = venue/tempat spesifik (dropdown dari tabel lokasis)
            $table->string('lokasi')->nullable();

            // ✅ kota = admin ketik manual (Semarang, Jakarta, dll)
            $table->string('kota')->nullable();

            $table->dateTime('waktu');

            // simpan path gambar
            $table->string('gambar')->nullable();

            $table->timestamps();

            // index biar query filter/urutan makin sat set
            $table->index(['kategori_id', 'waktu']);
            $table->index(['kota']);
            $table->index(['lokasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
