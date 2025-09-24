<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Sales\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class BiddingCheckoutController extends Controller
{
    /**
     * Display the bidding checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bid = session('current_bid');

        if (!$bid) {
            return redirect()->route('shop.bidinglist.biding_list')
                ->with('error', 'No active bid found. Please place a bid first.');
        }

        // Check if auction is still active
        $endTime = strtotime($bid['auction_end']);
        if (time() > $endTime) {
            session()->forget('current_bid');
            return redirect()->route('shop.bidinglist.biding_list')
                ->with('error', 'Auction has ended. Please place a new bid.');
        }

        return view('shop::bidding.checkout', compact('bid'));
    }

    /**
     * Process the bidding payment.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment()
    {
        $bid = session('current_bid');

        if (!$bid) {
            return redirect()->route('shop.bidinglist.biding_list')
                ->with('error', 'No active bid found.');
        }

        // Check if auction is still active
        $endTime = strtotime($bid['auction_end']);
        if (time() > $endTime) {
            session()->forget('current_bid');
            return redirect()->route('shop.bidinglist.biding_list')
                ->with('error', 'Auction has ended. Please place a new bid.');
        }

        try {
            // For Stripe integration, we'll handle payment via the Stripe controller
            // This method will now just validate and redirect to Stripe
            return redirect()->route('shop.stripe.bidding.success', [
                'payment_intent' => request('payment_intent_id')
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process bid: ' . $e->getMessage());
        }
    }

    /**
     * Display success page.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('shop::bidding.success');
    }

    /**
     * Cancel bid and clear session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        session()->forget('current_bid');
        return redirect()->route('shop.bidinglist.biding_list')
            ->with('info', 'Bid cancelled.');
    }
}
