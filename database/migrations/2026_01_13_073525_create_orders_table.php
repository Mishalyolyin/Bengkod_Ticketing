<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // metode pembayaran (nggak pakai FK constraint biar aman urutan migration kamu)
            $table->foreignId('payment_type_id')->nullable()->index();

            $table->dateTime('order_date');
            $table->decimal('total_price', 12, 2)->default(0);

            // ini yang bikin tombol "Bayar Sekarang" ga error
            $table->string('status')->default('pending')->index(); // pending | paid
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
