<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreignId('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreignId('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('waiter_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('total', 8, 2)->default(0);
            $table->decimal('paid', 8, 2)->default(0);
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
