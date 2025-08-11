<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Bidding\BiddingProductController;

Route::prefix('bidding')->group(function () {
    Route::controller(BiddingProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('admin.bidding.products.index');
        Route::get('create', 'create')->name('admin.bidding.products.create');
    });
});

