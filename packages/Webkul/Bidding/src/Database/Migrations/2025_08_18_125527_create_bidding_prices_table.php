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
        Schema::create('bidding_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bidding_product_id'); // foreign key to bidding_products
            $table->decimal('product_price', 15, 2); // price of the product
            $table->string('currency', 10); // e.g., USD, LKR
            $table->decimal('starting_price', 15, 2);
            $table->decimal('minimum_increment', 15, 2); // fixed typo: "minimum_increment_file" → "minimum_increment"
            $table->decimal('reserve_price', 15, 2)->nullable();
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('c_date')->useCurrent();
            $table->timestamp('m_date')->useCurrent()->useCurrentOnUpdate();

            // Optional foreign key constraint
            // $table->foreign('bidding_product_id')->references('id')->on('bidding_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_prices');
    }
};
