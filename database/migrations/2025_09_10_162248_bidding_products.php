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
        Schema::create('bidding_products', function (Blueprint $table) {
            $table->bigIncrements('bid_pro_id')->unique();
            $table->bigInteger('product_id');
            $table->string('product_name', 255);
            $table->bigInteger('category_id')->unique();
            $table->decimal('carat_weight', 8, 2);
            $table->string('color', 255);
            $table->string('shape', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('c_date')->useCurrent();
            $table->timestamp('m_date')->useCurrent()->useCurrentOnUpdate();
            
            // Add indexes for better performance
            $table->index('product_id');
            $table->index('category_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_products');
    }
};