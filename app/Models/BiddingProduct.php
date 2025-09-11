<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingProduct extends Model
{
    protected $fillable = [
        'name',
        'category',
        'status',
        'price',
        'starting_bid',
        'description',
        'images',
    ];

    protected $casts = [
        'images' => 'array',  // Automatically convert images JSON to array
    ];
}
