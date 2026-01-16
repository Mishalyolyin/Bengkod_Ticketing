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

            $table->dateTime('order_date');
            $table->decimal('total_price', 12, 2)->default(0);

            $table->timestamps();

            $table->index(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
