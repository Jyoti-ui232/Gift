<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, processing, shipped, delivered, cancelled
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->string('payment_method')->nullable(); // cod, online, upi
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_id')->nullable();
            // Recipient info
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('recipient_address');
            $table->string('recipient_city');
            $table->string('recipient_state');
            $table->string('recipient_pincode');
            // Delivery
            $table->string('delivery_type')->default('standard');
            $table->date('delivery_date')->nullable();
            $table->string('delivery_slot')->nullable();
            // Message
            $table->text('gift_message')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
