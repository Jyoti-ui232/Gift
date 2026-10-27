<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('occasion_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('occasion_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            $table->foreign('occasion_id')->references('id')->on('occasions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('occasion_product'); }
};
