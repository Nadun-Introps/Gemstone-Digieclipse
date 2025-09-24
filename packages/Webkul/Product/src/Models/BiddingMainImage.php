<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingMainImage extends Model
{
    protected $table = 'product_images';
    protected $guarded = [];

    public function biddingProducts()
    {
        return $this->hasMany(BiddingProduct::class, 'product_id', 'product_id');
    }
}