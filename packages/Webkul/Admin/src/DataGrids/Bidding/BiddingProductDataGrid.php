<?php

namespace Webkul\Admin\DataGrids\Bidding;

use Webkul\DataGrid\DataGrid;

class BiddingProductDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = \DB::table('products')
            ->select(
                'products.id',
                'products.name as product',
                'categories.name as category',
                'products.price'
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

        $this->addFilter('product', 'products.name');
        $this->addFilter('category', 'categories.name');
        $this->addFilter('price', 'products.price');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Add columns.
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'product',
            'label'      => 'Product',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'category',
            'label'      => 'Category',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'price',
            'label'      => 'Price',
            'type'       => 'price',
            'sortable'   => true,
        ]);
    }

    /**
     * Add actions.
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => 'Edit',
            'method' => 'GET',
            'route'  => 'admin.bidding.products.edit',
            'icon'   => 'icon-edit',
        ]);

        $this->addAction([
            'title'        => 'Delete',
            'method'       => 'DELETE',
            'route'        => 'admin.bidding.products.delete',
            'icon'         => 'icon-delete',
            'confirm_text' => 'Are you sure you want to delete this product?',
        ]);
    }
}
