<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Product\Models\BiddingProduct;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;
use Carbon\Carbon;

class HomeController extends Controller
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

    /**
     * Loads the home page for the storefront.
     *
     * @return \Illuminate\View\View
     */
   public function index()
    {
        visitor()->visit();

        $customizations = $this->themeCustomizationRepository->orderBy('sort_order')->findWhere([
            'status'     => self::STATUS,
            'channel_id' => core()->getCurrentChannel()->id,
            'theme_code' => core()->getCurrentChannel()->theme,
        ]);

        $categoryApiUrl = route('shop.api.categories.index');

        // ----------------------------
        // Fetch auctions to show on home
        // ----------------------------
        $auctions = BiddingProduct::with(['activePrice', 'mainImage'])
            ->where('status', 'active')
            ->get()
            ->filter(function ($p) {
                // Only include items that have an active price and main image
                return $p->activePrice && $p->mainImage;
            })
            ->map(function ($p) {
                $price = $p->activePrice;
                $img   = $p->mainImage->path ?? null; // <-- Use `path` column from product_images

                // Build Carbon datetimes and convert to ISO8601 for client-side JS
                try {
                    $start = $price->start_date && $price->start_time
                        ? Carbon::parse($price->start_date . ' ' . $price->start_time)
                        : null;
                } catch (\Exception $e) {
                    $start = null;
                }

                try {
                    $end = $price->end_date && $price->end_time
                        ? Carbon::parse($price->end_date . ' ' . $price->end_time)
                        : null;
                } catch (\Exception $e) {
                    $end = null;
                }

                return [
                    'id'           => $p->bid_pro_id, // <-- Use correct PK
                    'product_name' => $p->product_name,
                    'image'        => $img ? asset('storage/' . $img) : asset('images/placeholder.png'),
                    'price'        => (float) ($price->product_price ?? 0),
                    'currency'     => $price->currency ?? '',
                    'start'        => $start ? $start->toIso8601String() : null,
                    'end'          => $end ? $end->toIso8601String() : null,
                ];
            })
            ->values();

        // ----------------------------
        // Pass auctions into the view
        // ----------------------------
        return view('shop::home.index', compact('customizations', 'categoryApiUrl', 'auctions'));
    }
    /**
     * Loads the home page for the storefront if something wrong.
     *
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    /**
     * Summary of contact.
     *
     * @return \Illuminate\View\View
     */
    public function contactUs()
    {
        return view('shop::home.contact-us');
    }

    /**
     * Summary of store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContactUsMail(ContactRequest $contactRequest)
    {
        try {
            Mail::queue(new ContactUs($contactRequest->only([
                'name',
                'email',
                'contact',
                'message',
            ])));

            session()->flash('success', trans('shop::app.home.thanks-for-contact'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            report($e);
        }

        return back();
    }
}
