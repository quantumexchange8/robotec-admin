<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AutoTrading extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'meta_login',
        'trading_account_id',
        'transaction_id',
        'investment_amount',
        'period',
        'status',
        'matured_at',
        'cumulative_pamm_return',
        'cumulative_amount',
        'cumulative_earning'
    ];

}
