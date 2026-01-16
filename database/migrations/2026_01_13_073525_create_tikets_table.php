<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('tipe', 20); // premium | reguler
            $table->decimal('harga', 12, 2);
            $table->unsignedInteger('stok')->default(0);

            $table->timestamps();

            $table->index(['event_id', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
