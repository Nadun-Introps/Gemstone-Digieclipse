<?php

namespace Webkul\Bidding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Bidding\Contracts\BiddingPrice as BiddingPriceContract;

class BiddingPrice extends Model implements BiddingPriceContract
{
    protected $table = 'bidding_prices';

    protected $fillable = [
        'bidding_product_id',
        'product_price',
        'currency',
        'starting_price',
        'minimum_increment',
        'reserve_price',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'status',
        'c_date',
        'm_date'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(BiddingProductProxy::modelClass(), 'bidding_product_id');
    }
}
