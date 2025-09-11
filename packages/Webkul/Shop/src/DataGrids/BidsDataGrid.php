<?php

namespace Webkul\Shop\DataGrids;

use Webkul\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class BidsDataGrid extends DataGrid
{
    /**
     * Primary key.
     */
    protected string $primaryKey = 'bub_id';

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('bidding_user_bids')
            ->select(
                'bidding_user_bids.bub_id',
                'bidding_user_bids.bidding_id',
                'bidding_user_bids.bid_amount',
                'bidding_user_bids.payment_status',
                'bidding_user_bids.bidding_status',
                'bidding_user_bids.created_at',
                'bidding_products.product_name',
                'bidding_products.carat_weight',
                'bidding_products.color',
                'bidding_products.shape',
                'bidding_prices.end_date' 
            )
            ->leftJoin('bidding_products', 'bidding_user_bids.bidding_id', '=', 'bidding_products.bid_pro_id')
            ->leftJoin('bidding_prices', 'bidding_products.bid_pro_id', '=', 'bidding_prices.bidding_product_id')
            ->where('bidding_user_bids.user_id', auth()->guard('customer')->user()->id);

        return $queryBuilder;
    }


    /**
     * Prepare columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('shop::app.customers.account.orders.bids.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'bid_amount',
            'label' => trans('shop::app.customers.account.orders.bids.bid-amount'),
            'type' => 'decimal',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'bid_amount',
            'label' => trans('shop::app.customers.account.orders.bids.highest-bid'),
            'type' => 'decimal',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'end_date',
            'label' => trans('shop::app.customers.account.orders.bids.bid-date'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'payment_status',
            'label' => trans('shop::app.customers.account.orders.bids.payment-status'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure' => function ($row) {
                return ucfirst($row->payment_status);
            },
        ]);

        $this->addColumn([
            'index' => 'bidding_status',
            'label' => trans('shop::app.customers.account.orders.bids.status'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure' => function ($row) {
                return ucfirst($row->bidding_status);
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {

    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions()
    {
        // No mass actions needed for bids
    }
}