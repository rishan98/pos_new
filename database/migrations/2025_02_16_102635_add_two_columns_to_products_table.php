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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('discount_type')->after('price')->default(0)->comment('0: None, 1: Fixed, 2: Percentage');
            $table->decimal('discount', 8, 2)->after('discount_type')->default(0);
            $table->integer('minimum_quantity')->after('discount')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('discount_type');
            $table->dropColumn('discount');
            $table->dropColumn('minimum_quantity');
        });
    }
};
