<?php

namespace Webkul\Bidding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Bidding\Contracts\BiddingMainImage as BiddingMainImageContract;

class BiddingMainImage extends Model implements BiddingMainImageContract
{
    protected $table = 'bidding_main_images';

    protected $fillable = [
        'bidding_product_id',
        'image',
        'status',
        'c_date',
        'm_date'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(BiddingProductProxy::modelClass(), 'bidding_product_id');
    }
}
