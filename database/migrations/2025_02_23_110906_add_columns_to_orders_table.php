<?php

use App\Models\Customer;
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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_amount', 8, 2)->after('user_id');
            $table->decimal('total_discount', 8, 2)->after('total_amount')->default(0);
            $table->integer('payment_status')->after('total_discount')->default(0)->comment('0: Not Paid, 1: Paid Partially, 2: Paid Fully');
            $table->decimal('paid_amount', 8, 2)->after('payment_status')->default(0);
            $table->integer('return_status')->after('payment_status')->default(0)->comment('0: Not Return, 1: Return Partially, 2: Return Fully');
            $table->string('order_number')->nullable()->after('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_amount');
            $table->dropColumn('total_discount');
            $table->dropColumn('payment_status');
            $table->dropColumn('paid_amount');
            $table->dropColumn('is_return');
            $table->dropColumn('order_number');
        });
    }
};
