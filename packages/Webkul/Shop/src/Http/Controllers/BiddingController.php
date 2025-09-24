<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BiddingController extends Controller
{
    /**
     * Using const variable for status
     */
    const STATUS = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ThemeCustomizationRepository $themeCustomizationRepository) {}

    public function index($id)
    {
        // Fetch bidding product details
        $biddingProduct = DB::table('bidding_products as bp')
            ->select(
                'bp.bid_pro_id',
                'bp.product_id', // Add product_id to the select
                'bp.product_name',
                'bp.description',
                'bp.status',
                'bpr.product_price as price',
                'bpr.start_date',
                'bpr.start_time',
                'bpr.end_date',
                'bpr.end_time',
                'bpr.minimum_increment',
                'bpr.reserve_price'
            )
            ->leftJoin('bidding_prices as bpr', 'bp.bid_pro_id', '=', 'bpr.bidding_product_id')
            ->where('bp.bid_pro_id', $id)
            ->where('bp.status', '!=', 'deleted')
            ->first();

        if (!$biddingProduct) {
            abort(404);
        }

        // Get product images
        $productImages = [];
        if ($biddingProduct->product_id) {
            $productImages = DB::table('product_images')
                ->where('product_id', $biddingProduct->product_id)
                ->orderBy('position', 'asc')
                ->get();
        }

        // Get bidding history
        $biddingHistory = DB::table('bidding_user_bids as bub')
            ->select(
                'bub.created_at',
                'bub.bid_amount',
                'u.name as customer_name'
            )
            ->leftJoin('users as u', 'bub.user_id', '=', 'u.id')
            ->where('bub.bidding_id', $id)
            ->orderBy('bub.created_at', 'desc')
            ->get();

        // Get current highest bid
        $currentBid = DB::table('bidding_user_bids')
            ->where('bidding_id', $id)
            ->max('bid_amount');

        return view('shop::bidding.bidding_single', compact('biddingProduct', 'productImages', 'biddingHistory', 'currentBid'));
    }

    public function placeBid($id)
    {
        // Validate the bid
        $validated = request()->validate([
            'bid_amount' => 'required|numeric|min:0',
        ]);

        // Get bidding product details
        $biddingProduct = DB::table('bidding_products as bp')
            ->select(
                'bp.bid_pro_id',
                'bpr.product_price as price',
                'bpr.minimum_increment',
                'bpr.end_date',
                'bpr.end_time'
            )
            ->leftJoin('bidding_prices as bpr', 'bp.bid_pro_id', '=', 'bpr.bidding_product_id')
            ->where('bp.bid_pro_id', $id)
            ->where('bp.status', 'active')
            ->first();

        if (!$biddingProduct) {
            return redirect()->back()->with('error', 'Auction not found or not active.');
        }

        // Check if auction has ended
        $endTime = strtotime($biddingProduct->end_date . ' ' . $biddingProduct->end_time);
        if (time() > $endTime) {
            return redirect()->back()->with('error', 'Auction has ended.');
        }

        // Get current highest bid
        $currentBid = DB::table('bidding_user_bids')
            ->where('bidding_id', $id)
            ->max('bid_amount');

        // Validate bid amount
        $minBid = $currentBid ? $currentBid + $biddingProduct->minimum_increment : $biddingProduct->price;

        if ($validated['bid_amount'] < $minBid) {
            return redirect()->back()->with('error', 'Bid amount must be at least ' . $minBid);
        }

        // Save the bid
        DB::table('bidding_user_bids')->insert([
            'bidding_id' => $id,
            'user_id' => auth()->id(),
            'bid_amount' => $validated['bid_amount'],
            'payment_status' => 'pending',
            'bidding_status' => 'ongoing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Bid placed successfully!');
    }

    public function addToCart($id)
    {
        // Validate the bid
        $validated = request()->validate([
            'bid_amount' => 'required|numeric|min:0',
        ]);

        // Get bidding product details
        $biddingProduct = DB::table('bidding_products as bp')
            ->select(
                'bp.bid_pro_id',
                'bp.product_name',
                'bp.product_id',
                'bpr.product_price as price',
                'bpr.minimum_increment',
                'bpr.end_date',
                'bpr.end_time'
            )
            ->leftJoin('bidding_prices as bpr', 'bp.bid_pro_id', '=', 'bpr.bidding_product_id')
            ->where('bp.bid_pro_id', $id)
            ->where('bp.status', 'active')
            ->first();

        if (!$biddingProduct) {
            return redirect()->back()->with('error', 'Auction not found or not active.');
        }

        // Check if auction has ended
        $endTime = strtotime($biddingProduct->end_date . ' ' . $biddingProduct->end_time);
        if (time() > $endTime) {
            return redirect()->back()->with('error', 'Auction has ended.');
        }

        // Get current highest bid
        $currentBid = DB::table('bidding_user_bids')
            ->where('bidding_id', $id)
            ->max('bid_amount');

        // Validate bid amount
        $minBid = $currentBid ? $currentBid + $biddingProduct->minimum_increment : $biddingProduct->price;

        if ($validated['bid_amount'] < $minBid) {
            return redirect()->back()->with('error', 'Bid amount must be at least ' . $minBid);
        }

        try {
            // Generate a unique session ID for this bid
            $sessionId = session()->getId() . '_' . time() . '_' . $id;

            // Save the bid to database with pending payment status
            $bidId = DB::table('bidding_user_bids')->insertGetId([
                'bidding_id' => $biddingProduct->bid_pro_id,
                'user_id' => auth()->id(),
                'session_id' => $sessionId,
                'bid_amount' => $validated['bid_amount'],
                'payment_status' => 'pending',
                'bidding_status' => 'ongoing',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Store bid information in session
            session()->put('current_bid', [
                'bidding_id' => $biddingProduct->bid_pro_id,
                'bid_id' => $bidId, // Store the database bid ID
                'session_id' => $sessionId, // Store the session ID
                'product_id' => $biddingProduct->product_id,
                'bid_amount' => $validated['bid_amount'],
                'product_name' => $biddingProduct->product_name,
                'auction_end' => $biddingProduct->end_date . ' ' . $biddingProduct->end_time,
                'sku' => 'BID-' . $biddingProduct->bid_pro_id,
                'min_bid' => $minBid,
                'created_at' => now()->toDateTimeString()
            ]);

            // Redirect to bidding checkout
            return redirect()->route('shop.bidding.checkout');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to process bid: ' . $e->getMessage());
        }
    }
}
