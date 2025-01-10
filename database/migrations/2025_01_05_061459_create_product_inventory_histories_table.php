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
        Schema::create('product_inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_inventory_id');
            $table->foreign('product_inventory_id')->references('id')->on('product_inventories')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('operation')->default(0)->comment('0: Initial, 1: Add, 2: Remove');
            $table->integer('quantity')->default(0);
            $table->integer('running_quantity')->default(0);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventory_histories');
    }
};
