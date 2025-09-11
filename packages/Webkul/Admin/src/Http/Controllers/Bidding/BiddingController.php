<?php

namespace Webkul\Admin\Http\Controllers\Bidding;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\DataGrids\Bidding\BiddingDataGrid;
use Illuminate\Support\Facades\DB;

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
        $biddingProduct = DB::table('bidding_products')->where('id', $id)->first();

        return response()->json($biddingProduct);
    }

    /**
     * View a bidding product
     */
    public function view($id)
    {
        // Return view page
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
