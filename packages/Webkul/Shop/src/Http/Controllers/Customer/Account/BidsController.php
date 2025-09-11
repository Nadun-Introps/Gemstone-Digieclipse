<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Webkul\Shop\DataGrids\BidsDataGrid;
use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BidsController extends Controller
{
    /**
     * Display the customer's bids.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(BidsDataGrid::class)->process();
        }

        return view('shop::customers.account.bids.index');
    }

    /**
     * View individual bid details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    // public function view($id)
    // {
    //     $bid = DB::table('bidding_user_bids')
    //         ->where('bub_id', $id)
    //         ->where('user_id', auth()->guard('customer')->user()->id)
    //         ->first();

    //     if (! $bid) {
    //         abort(404);
    //     }

    //     return view('shop::customers.account.bids.view', compact('bid'));
    // }
}