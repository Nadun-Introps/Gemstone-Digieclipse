<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingProduct extends Model
{
    protected $table = 'bidding_products';
    protected $guarded = [];

    // one active price (latest active)
    public function activePrice()
    {
        return $this->hasOne(BiddingPrice::class, 'bidding_product_id')
                    ->where('status', 'active')
                    ->orderBy('id', 'desc');
    }

    public function mainImage()
    {
        return $this->hasOne(BiddingMainImage::class, 'bidding_product_id')
                    ->where('status', 'active')
                    ->orderBy('id', 'asc');
    }

    // all prices (if needed)
    public function prices()
    {
        return $this->hasMany(BiddingPrice::class, 'bidding_product_id');
    }

    public function images()
    {
        return $this->hasMany(BiddingMainImage::class, 'bidding_product_id');
    }

    
}