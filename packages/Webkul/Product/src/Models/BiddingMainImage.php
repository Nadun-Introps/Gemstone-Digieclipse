<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingMainImage extends Model
{
    protected $table = 'bidding_main_images';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(BiddingProduct::class, 'bidding_product_id');
    }
}
