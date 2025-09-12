<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Bidding\BiddingController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin'], function () {
    Route::get('bidding', [BiddingController::class, 'index'])->name('admin.bidding.index');
    Route::get('bidding/create', [BiddingController::class, 'create'])->name('admin.bidding.create');
    Route::get('bidding/edit/{id}', [BiddingController::class, 'edit'])->name('admin.bidding.edit');
    Route::get('bidding/view/{id}', [BiddingController::class, 'view'])->name('admin.bidding.view');
    Route::delete('bidding/delete/{id}', [BiddingController::class, 'delete'])->name('admin.bidding.delete');
    Route::post('bidding/mass-delete', [BiddingController::class, 'massDelete'])->name('admin.bidding.mass_delete');
    Route::post('bidding/mass-update-status', [BiddingController::class, 'massUpdateStatus'])->name('admin.bidding.mass_update_status');
    Route::post('bidding/pause/{id}', [BiddingController::class, 'pause'])->name('admin.bidding.pause');
    Route::post('bidding/mass-pause', [BiddingController::class, 'massPause'])->name('admin.bidding.mass_pause');
    Route::put('bidding/update/{id}', [BiddingController::class, 'update'])->name('admin.bidding.update');
});
