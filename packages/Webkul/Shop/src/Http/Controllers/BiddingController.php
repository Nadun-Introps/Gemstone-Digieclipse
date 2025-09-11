<?php

namespace Webkul\Shop\Http\Controllers;

// use Illuminate\Support\Facades\Mail;
// use Webkul\Shop\Http\Requests\ContactRequest;
// use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

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

    public function index()
    {
       return view('shop::bidding.bidding_single');
    } 
}
