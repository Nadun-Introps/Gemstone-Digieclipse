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
        Schema::table('bidding_user_bids', function (Blueprint $table) {
            // Add 'session_id' after 'bidding_id'
            $table->string('session_id', 255)->nullable()->after('bidding_id');

            // Add 'stripe_payment_intent_id' after 'payment_details'
            $table->string('stripe_payment_intent_id', 255)->nullable()->after('payment_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bidding_user_bids', function (Blueprint $table) {
            $table->dropColumn(['session_id', 'stripe_payment_intent_id']);
        });
    }
};
