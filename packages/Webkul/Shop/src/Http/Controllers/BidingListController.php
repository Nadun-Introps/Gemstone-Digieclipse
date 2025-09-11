<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class BidingListController extends Controller
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

        return view('shop::bidinglist.biding_list');
    }

    /**
     * Loads the home page for the storefront if something wrong.
     *
     * @return \Exception
     */


    /**
     * Summary of contact.
     *
     * @return \Illuminate\View\View
     */

    /**
     * Summary of store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
   
}
