<?php

namespace Webkul\Admin\Http\Controllers\Bidding;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiddingProductController extends Controller
{
    public function index()
    {
        return view('admin::bidding.products.index');
    }

    public function create()
    {
        return view('admin.bidding.products.create');
    }

}
