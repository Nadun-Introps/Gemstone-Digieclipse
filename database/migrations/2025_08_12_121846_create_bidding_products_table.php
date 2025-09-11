<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bidding_products', function (Blueprint $table) {
            $table->id(); // Auto increment primary key
            $table->string('name'); // Product name
            $table->string('category'); // Product category name or use category_id if relational
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status
            $table->decimal('price', 12, 2); // Product price
            $table->decimal('starting_bid', 12, 2); // Starting bid price
            $table->text('description')->nullable(); // Optional description
            $table->json('images')->nullable(); // JSON array of image filenames
            $table->timestamps(); // created_at and updated_at timestamps
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
