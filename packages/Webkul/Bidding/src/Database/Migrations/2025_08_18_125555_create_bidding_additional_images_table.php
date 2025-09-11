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
        Schema::create('bidding_additional_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bidding_product_id'); // foreign key to bidding_products
            $table->string('image'); // path or filename of the additional image
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
        Schema::dropIfExists('bidding_additional_images');
    }
};
