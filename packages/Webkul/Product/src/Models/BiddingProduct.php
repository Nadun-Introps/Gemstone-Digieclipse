<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingProduct extends Model
{
    protected $table = 'bidding_products';
    protected $primaryKey = 'bid_pro_id'; // important
    protected $guarded = [];

    // Relation with bidding_prices
    public function prices()
    {
        return $this->hasMany(BiddingPrice::class, 'bidding_product_id', 'bid_pro_id');
    }

    public function activePrice()
    {
        return $this->hasOne(BiddingPrice::class, 'bidding_product_id', 'bid_pro_id')
                    ->where('status', 'active')
                    ->orderBy('id', 'desc');
    }

    // Relation with product_images
    public function images()
    {
        return $this->hasMany(BiddingMainImage::class, 'product_id', 'product_id');
    }

    public function mainImage()
    {
        return $this->hasOne(BiddingMainImage::class, 'product_id', 'product_id')
                    ->orderBy('position', 'asc');
    }
}