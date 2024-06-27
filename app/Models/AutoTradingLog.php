<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoTradingLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'auto_trading_id',
        'old_pamm',
        'new_pamm',
        'old_amount',
        'new_amount',
        'old_earning',
        'new_earning',
        'status',
        'remarks',
    ];
}
