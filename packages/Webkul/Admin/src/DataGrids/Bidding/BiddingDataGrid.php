<?php

namespace Webkul\Admin\DataGrids\Bidding;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class BiddingDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('bidding_products as bp')
            ->select(
                'bp.bid_pro_id as id',
                'bp.product_name as name',
                'bp.status as auction_status',
                'bpr.product_price as price',
                DB::raw('NULL as current_bid'), // Will be empty for now
                'bpr.start_date',
                'bpr.start_time',
                'bpr.end_date',
                'bpr.end_time',
                DB::raw('NULL as product_image'), // Will be empty for now
                'bp.c_date as created_at',
                'bp.m_date as updated_at',
                DB::raw('ROW_NUMBER() OVER (ORDER BY bp.bid_pro_id) as row_number') // Add row number
            )
            ->leftJoin('bidding_prices as bpr', 'bp.bid_pro_id', '=', 'bpr.bidding_product_id')
            ->where('bp.status', '!=', 'deleted'); // Exclude deleted records

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'row_number',
            'label'      => trans('admin::app.bidding.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_image',
            'label'      => trans('admin::app.bidding.index.datagrid.product-image'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                // Empty for now as requested
                return '';
            },
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.bidding.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'auction_status',
            'label'      => trans('admin::app.bidding.index.datagrid.auction-status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $statuses = [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'deleted' => 'Deleted',
                ];

                return $statuses[$row->auction_status] ?? ucfirst($row->auction_status);
            },
        ]);

        $this->addColumn([
            'index'      => 'price',
            'label'      => trans('admin::app.bidding.index.datagrid.price'),
            'type'       => 'decimal',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                return $row->price ?? 0;
            },
        ]);

        $this->addColumn([
            'index'      => 'current_bid',
            'label'      => trans('admin::app.bidding.index.datagrid.current-bid'),
            'type'       => 'decimal',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                // Empty for now as requested
                return '';
            },
        ]);

        $this->addColumn([
            'index'      => 'start_date',
            'label'      => trans('admin::app.bidding.index.datagrid.start-date'),
            'type'       => 'datetime',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                return $row->start_date && $row->start_time
                    ? $row->start_date . ' ' . $row->start_time
                    : $row->start_date;
            },
        ]);

        $this->addColumn([
            'index'      => 'end_date',
            'label'      => trans('admin::app.bidding.index.datagrid.end-date'),
            'type'       => 'datetime',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                return $row->end_date && $row->end_time
                    ? $row->end_date . ' ' . $row->end_time
                    : $row->end_date;
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {
        $this->addAction([
            'index'  => 'view',
            'icon'   => 'icon-view',
            'title'  => trans('admin::app.bidding.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.bidding.view', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'pause',
            'icon'   => 'icon-pause', // or try 'icon-pause-circle'
            'title'  => trans('admin::app.bidding.index.datagrid.pause'),
            'method' => 'POST',
            'url'    => function ($row) {
                return route('admin.bidding.pause', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.bidding.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return "javascript:openEditModal({$row->id})";
            },
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.bidding.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.bidding.delete', $row->id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'title'  => trans('admin::app.bidding.index.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.bidding.mass_delete'),
        ]);

        $this->addMassAction([
            'title'  => trans('admin::app.bidding.index.datagrid.pause'),
            'method' => 'POST',
            'url'    => route('admin.bidding.mass_pause'),
        ]);

        $this->addMassAction([
            'title'   => trans('admin::app.bidding.index.datagrid.update-status'),
            'method'  => 'POST',
            'url'     => route('admin.bidding.mass_update_status'),
            'options' => [
                [
                    'label' => trans('admin::app.bidding.index.datagrid.active'),
                    'value' => 'active',
                ],
                [
                    'label' => trans('admin::app.bidding.index.datagrid.inactive'),
                    'value' => 'inactive',
                ],
                [
                    'label' => trans('admin::app.bidding.index.datagrid.paused'),
                    'value' => 'paused',
                ],
            ],
        ]);
    }
}
