<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Bidding\BiddingProductController;

Route::prefix('bidding')->group(function () {
    Route::controller(BiddingProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('admin.bidding.products.index');
        Route::get('create', 'create')->name('admin.bidding.products.create');
        Route::post('/store', [BiddingProductController::class, 'store'])->name('admin.bidding.products.store');
        //Route::get('/edit/{id}', [BiddingProductController::class, 'edit'])->name('edit');
        //Route::put('/update/{id}', [BiddingProductController::class, 'update'])->name('update');
        //Route::delete('/delete/{id}', [BiddingProductController::class, 'destroy'])->name('delete');
    });
});

