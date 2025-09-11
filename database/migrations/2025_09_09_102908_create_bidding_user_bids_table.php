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
        Schema::create('bidding_user_bids', function (Blueprint $table) {
            $table->id('bub_id'); // Primary Key, Auto Increment
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('bidding_id')->nullable();
            $table->decimal('bid_amount', 10, 2)->nullable();
            $table->string('payment_status', 45)->nullable();
            $table->text('payment_details')->nullable();
            $table->string('bidding_status', 45)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_user_bids');
    }
};
