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
        Schema::table('bidding_products', function (Blueprint $table) {
            // Rename 'id' column to 'bid_pro_id'
            $table->renameColumn('id', 'bid_pro_id');

            // Add new 'product_id' column after 'bid_pro_id'
            $table->unsignedBigInteger('product_id')->after('bid_pro_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bidding_products', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('bid_pro_id', 'id');
            $table->dropColumn('product_id');
        });
    }
};
