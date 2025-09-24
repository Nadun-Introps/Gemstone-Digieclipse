<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\BidingListController;
use Webkul\Shop\Http\Controllers\BookingProductController;
use Webkul\Shop\Http\Controllers\CompareController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\PageController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;
use Webkul\Shop\Http\Controllers\BiddingController;
use Webkul\Shop\Http\Controllers\BiddingCheckoutController;
use Webkul\Shop\Http\Controllers\StripeController;
use Webkul\Shop\Http\Controllers\BiddingStripeController;

/**
 * CMS pages.
 */
Route::get('page/{slug}', [PageController::class, 'view'])
    ->name('shop.cms.page')
    ->middleware('cache.response');

/**
 * Fallback route.
 */
Route::fallback(ProductsCategoriesProxyController::class.'@index')
    ->name('shop.product_or_category.index')
    ->middleware('cache.response');

/**
 * Store front home.
 */
Route::get('/', [HomeController::class, 'index'])
    ->name('shop.home.index')
    ->middleware('cache.response');

Route::get('contact-us', [HomeController::class, 'contactUs'])
    ->name('shop.home.contact_us')
    ->middleware('cache.response');

Route::post('contact-us/send-mail', [HomeController::class, 'sendContactUsMail'])
    ->name('shop.home.contact_us.send_mail')
    ->middleware('cache.response');

/**
 * Store front search.
 */
Route::get('search', [SearchController::class, 'index'])
    ->name('shop.search.index')
    ->middleware('cache.response');

Route::post('search/upload', [SearchController::class, 'upload'])->name('shop.search.upload');

/**
 * Subscription routes.
 */
Route::controller(SubscriptionController::class)->group(function () {
    Route::post('subscription', 'store')->name('shop.subscription.store');

    Route::get('subscription/{token}', 'destroy')->name('shop.subscription.destroy');
});

/**
 * Compare products
 */
Route::get('compare', [CompareController::class, 'index'])
    ->name('shop.compare.index')
    ->middleware('cache.response');

/**
 * Downloadable products
 */
Route::controller(ProductController::class)->group(function () {
    Route::get('downloadable/download-sample/{type}/{id}', 'downloadSample')->name('shop.downloadable.download_sample');

    Route::get('product/{id}/{attribute_id}', 'download')->name('shop.product.file.download');
});

/**
 * Booking products
 */
Route::get('booking-slots/{id}', [BookingProductController::class, 'index'])
    ->name('shop.booking-product.slots.index');

/**
 * Bidding
 */
Route::get('biding_list', [BidingListController::class, 'index'])
    ->name('shop.bidinglist.biding_list')
    ->middleware('cache.response');

Route::get('bidding-single/{id}', [BiddingController::class, 'index'])
    ->name('shop.bidding.bidding_single')
    ->middleware('cache.response');

Route::post('bidding-single/{id}/add-to-cart', [BiddingController::class, 'addToCart'])
    ->name('shop.bidding.add_to_cart');

// Bidding checkout routes
Route::prefix('bidding-checkout')->group(function () {
    Route::get('', [BiddingCheckoutController::class, 'index'])
        ->name('shop.bidding.checkout');

    Route::post('process', [BiddingCheckoutController::class, 'processPayment'])
        ->name('shop.bidding.checkout.process');

    Route::get('success', [BiddingCheckoutController::class, 'success'])
        ->name('shop.bidding.success');

    Route::get('cancel', [BiddingCheckoutController::class, 'cancel'])
        ->name('shop.bidding.checkout.cancel');
});

// Stripe routes
Route::prefix('stripe')->group(function () {
    Route::post('process-payment', [StripeController::class, 'processPayment'])
        ->name('shop.stripe.process_payment');

    Route::get('success', [StripeController::class, 'success'])
        ->name('shop.stripe.success');

    Route::get('cancel', [StripeController::class, 'cancel'])
        ->name('shop.stripe.cancel');

    // Bidding specific routes
    Route::post('bidding/process-payment', [BiddingStripeController::class, 'processBiddingPayment'])
        ->name('shop.stripe.bidding.process_payment');

    Route::get('bidding/success', [BiddingStripeController::class, 'biddingSuccess'])
        ->name('shop.stripe.bidding.success');

    // Bidding webhook
    Route::post('bidding/webhook', [BiddingStripeController::class, 'handleBiddingWebhook'])
        ->name('shop.stripe.bidding.webhook');

    // Regular Stripe webhook
    Route::post('webhook', [StripeController::class, 'webhook'])
        ->name('shop.stripe.webhook');
});

// Webhook routes without CSRF protection
Route::withoutMiddleware(['web'])->group(function () {
    Route::prefix('stripe-webhook')->group(function () {
        // Bidding webhook
        Route::post('bidding/webhook', [BiddingStripeController::class, 'handleBiddingWebhook'])
            ->name('shop.stripe.bidding.webhook');

        // Regular Stripe webhook
        Route::post('webhook', [StripeController::class, 'webhook'])
            ->name('shop.stripe.webhook');
    });
});

Route::middleware(['auth:customer'])->group(function () {
    Route::post('bidding-single/{id}/add-to-cart', [BiddingController::class, 'addToCart'])
        ->name('shop.bidding.add_to_cart');

    Route::post('bidding-single/{id}/bid', [BiddingController::class, 'placeBid'])
        ->name('shop.bidding.place_bid');
});
