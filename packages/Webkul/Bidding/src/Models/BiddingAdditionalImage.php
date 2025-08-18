<?php

namespace Webkul\Bidding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Bidding\Contracts\BiddingAdditionalImage as BiddingAdditionalImageContract;

class BiddingAdditionalImage extends Model implements BiddingAdditionalImageContract
{
    protected $table = 'bidding_additional_images';

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
