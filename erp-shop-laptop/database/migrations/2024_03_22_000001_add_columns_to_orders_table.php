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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm các cột mới
            $table->decimal('subtotal', 12, 2)->after('order_number');
            $table->decimal('tax', 12, 2)->default(0)->after('subtotal');
            $table->decimal('shipping', 12, 2)->default(0)->after('tax');
            
            // Thêm các cột mới
            $table->string('email')->nullable()->after('shipping_name');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Thêm cột name và total
            $table->string('name')->after('product_id');
            $table->decimal('total', 12, 2)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa các cột đã thêm
            $table->dropColumn([
                'subtotal',
                'tax',
                'shipping',
                'email',
                'payment_status',
                'payment_method'
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['name', 'total']);
        });
    }
}; 