<?php

namespace Webkul\Admin\Http\Controllers\Bidding;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\DataGrids\Bidding\BiddingDataGrid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BiddingController extends Controller
{
    /**
     * Display the bidding index view
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(BiddingDataGrid::class)->toJson();
        }

        return view('admin::bidding.index');
    }

    /**
     * Show the form for creating a new bidding product
     */
    public function create()
    {
        // Return create view
        return view('admin::bidding.create');
    }

    /**
     * Show the form for editing a bidding product
     */
    public function edit($id)
    {
        $biddingProduct = DB::table('bidding_products as bp')
            ->select(
                'bp.bid_pro_id',
                'bp.product_name',
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
            ->first();

        if (!$biddingProduct) {
            abort(404);
        }

        return view('admin::bidding.edit', compact('biddingProduct'));
    }
    /**
     * Update a bidding product
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'minimum_increment' => 'required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date|after:start_date',
            'end_time' => 'required',
            'status' => 'required|in:active,inactive,paused',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update bidding_products table
            DB::table('bidding_products')
                ->where('bid_pro_id', $id)
                ->update([
                    'product_name' => $request->product_name,
                    'status' => $request->status,
                    'm_date' => now(),
                ]);

            // Update bidding_prices table
            DB::table('bidding_prices')
                ->where('bidding_product_id', $id)
                ->update([
                    'product_price' => $request->price,
                    'minimum_increment' => $request->minimum_increment,
                    'reserve_price' => $request->reserve_price,
                    'start_date' => $request->start_date,
                    'start_time' => $request->start_time,
                    'end_date' => $request->end_date,
                    'end_time' => $request->end_time,
                ]);

            DB::commit();

            session()->flash('success', trans('admin::app.bidding.update-success'));

            return redirect()->route('admin.bidding.index');
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', trans('admin::app.bidding.update-failed'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * View a bidding product
     */
    public function view($id)
    {
        $biddingProduct = DB::table('bidding_products as bp')
            ->select(
                'bp.bid_pro_id',
                'bp.product_name',
                'bp.status',
                'bp.c_date as created_at',
                'bp.m_date as updated_at',
                'bpr.product_price as price',
                'bpr.minimum_increment',
                'bpr.reserve_price',
                'bpr.start_date',
                'bpr.start_time',
                'bpr.end_date',
                'bpr.end_time'
            )
            ->leftJoin('bidding_prices as bpr', 'bp.bid_pro_id', '=', 'bpr.bidding_product_id')
            ->where('bp.bid_pro_id', $id)
            ->first();

        if (!$biddingProduct) {
            abort(404);
        }

        // Get bidding history
        $biddingHistory = DB::table('bidding_user_bids as bub')
        ->select(
            'bub.created_at',
            'bub.bid_amount',
            DB::raw("CONCAT(c.first_name, ' ', c.last_name) as customer_name")
        )
        ->leftJoin('customers as c', 'bub.user_id', '=', 'c.id')
        ->where('bub.bidding_id', $id)
        ->orderBy('bub.created_at', 'desc')
        ->get();

        return view('admin::bidding.view', compact('biddingProduct', 'biddingHistory'));
    }

    /**
     * Delete a bidding product (update status to 'deleted')
     */
    public function delete($id)
    {
        try {
            // Update status to 'deleted' instead of deleting
            DB::table('bidding_products')->where('bid_pro_id', $id)->update(['status' => 'deleted']);

            return new JsonResponse([
                'message' => trans('admin::app.bidding.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.bidding.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass delete bidding products (update status to 'deleted')
     */
    public function massDelete()
    {
        $ids = request()->input('indices', []);

        try {
            // Update status to 'deleted' instead of deleting
            DB::table('bidding_products')->whereIn('bid_pro_id', $ids)->update(['status' => 'deleted']);

            return new JsonResponse([
                'message' => trans('admin::app.bidding.mass-delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.bidding.mass-delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass update status of bidding products
     */
    public function massUpdateStatus()
    {
        $ids = request()->input('indices', []);
        $status = request()->input('value', 'active');

        try {
            DB::table('bidding_products')->whereIn('bid_pro_id', $ids)->update(['status' => $status]);

            return new JsonResponse([
                'message' => trans('admin::app.bidding.mass-update-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.bidding.mass-update-failed'),
            ], 500);
        }
    }

    /**
     * Pause a bidding product
     */
    public function pause($id)
    {
        try {
            DB::table('bidding_products')->where('bid_pro_id', $id)->update(['status' => 'paused']);

            return new JsonResponse([
                'message' => trans('admin::app.bidding.pause-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.bidding.pause-failed'),
            ], 500);
        }
    }

    /**
     * Mass pause bidding products
     */
    public function massPause()
    {
        $ids = request()->input('indices', []);

        try {
            DB::table('bidding_products')->whereIn('bid_pro_id', $ids)->update(['status' => 'paused']);

            return new JsonResponse([
                'message' => trans('admin::app.bidding.mass-pause-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.bidding.mass-pause-failed'),
            ], 500);
        }
    }
}
