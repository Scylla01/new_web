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
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_address_id')->constrained('addresses')->onDelete('cascade');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_fee', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cod', 'bank_transfer', 'vnpay', 'momo']);
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->text('note')->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
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