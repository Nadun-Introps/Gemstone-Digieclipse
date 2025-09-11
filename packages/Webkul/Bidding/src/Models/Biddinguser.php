<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiddingUserBid extends Model
{
    use HasFactory;

    protected $table = 'bidding_user_bids';
    protected $primaryKey = 'bub_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false; // because created_at is datetime, updated_at is timestamp (not default format)

    protected $fillable = [
        'user_id',
        'bidding_id',
        'bid_amount',
        'payment_status',
        'payment_details',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
