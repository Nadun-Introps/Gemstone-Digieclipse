<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Webkul\Product\Models\BiddingProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Return a view with auctions (server-side rendered)
     */
    public function index()
    {
        // only products that have active price and an image
        $products = BiddingProduct::with(['activePrice', 'mainImage'])
            ->where('status', 'active')
            ->get()
            ->filter(function($p) {
                return $p->activePrice && $p->mainImage;
            })
            ->map(function($p) {
                $price = $p->activePrice;
                $img = $p->mainImage->image ?? null;

                // build start and end datetimes using Carbon
                try {
                    $start = Carbon::parse($price->start_date . ' ' . $price->start_time);
                    $end   = Carbon::parse($price->end_date   . ' ' . $price->end_time);
                } catch (\Exception $e) {
                    $start = null;
                    $end = null;
                }

                return (object)[
                    'id' => $p->id,
                    'product_name' => $p->product_name,
                    'image' => $img ? asset('storage/bid/' . $img) : asset('images/placeholder.png'),
                    'price' => $price->product_price,
                    'currency' => $price->currency,
                    'start' => $start ? $start->toIso8601String() : null,
                    'end' => $end ? $end->toIso8601String() : null,
                ];
            })
            ->values();

        return view('shop::home.auction', ['auctions' => $products]);
    }

    /**
     * Optional: JSON API that returns auction data
     */
    public function listJson()
    {
        $products = BiddingProduct::with(['activePrice', 'mainImage'])
            ->where('status', 'active')
            ->get()
            ->filter(function($p) {
                return $p->activePrice && $p->mainImage;
            })
            ->map(function($p) {
                $price = $p->activePrice;
                $img = $p->mainImage->image ?? null;

                $start = \Carbon\Carbon::parse($price->start_date . ' ' . $price->start_time);
                $end   = \Carbon\Carbon::parse($price->end_date . ' ' . $price->end_time);

                return [
                    'id' => $p->id,
                    'product_name' => $p->product_name,
                    'image' => $img ? asset('storage/bid/' . $img) : null,
                    'price' => (float) $price->product_price,
                    'currency' => $price->currency,
                    'start' => $start->toIso8601String(),
                    'end' => $end->toIso8601String(),
                ];
            })
            ->values();

        return response()->json($products);
    }
}
