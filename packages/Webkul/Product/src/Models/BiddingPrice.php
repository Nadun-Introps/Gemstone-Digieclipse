<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingPrice extends Model
{
    protected $table = 'bidding_prices';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(BiddingProduct::class, 'bidding_product_id');
    }
}
