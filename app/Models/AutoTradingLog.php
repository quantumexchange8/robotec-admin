<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoTradingLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
