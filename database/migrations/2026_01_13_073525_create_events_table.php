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
            $table->string('lokasi')->nullable();
            $table->dateTime('waktu');
            $table->string('gambar')->nullable(); // simpan path gambar

            $table->timestamps();

            $table->index(['kategori_id', 'waktu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
