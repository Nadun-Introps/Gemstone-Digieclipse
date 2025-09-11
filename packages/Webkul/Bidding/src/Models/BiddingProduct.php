<?php

namespace Webkul\Bidding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Bidding\Contracts\bidding as BiddingContract;

class BiddingProduct extends Model implements BiddingProductContract
{
   protected $table = 'bidding_products';
    protected $fillable = [
        'product_name',
        'category_id',
        'carat_weight',
        'color',
        'shape',
        'description',
        'status',
        'c_date',
        'm_date'
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(BiddingPriceProxy::modelClass(), 'bidding_product_id');
    }

    public function mainImages(): HasMany
    {
        return $this->hasMany(BiddingMainImageProxy::modelClass(), 'bidding_product_id');
    }

    public function additionalImages(): HasMany
    {
        return $this->hasMany(BiddingAdditionalImageProxy::modelClass(), 'bidding_product_id');
    }

}
