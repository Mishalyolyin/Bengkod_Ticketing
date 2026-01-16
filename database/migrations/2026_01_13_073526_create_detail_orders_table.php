<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('tiket_id')
                ->constrained('tikets')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('jumlah');
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

            $table->index(['order_id', 'tiket_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_orders');
    }
};
