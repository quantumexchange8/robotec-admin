<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommissionPayout extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'upline_id',
        'downline_id',
        'transaction_id',
        'amount',
        'status',
        'remarks',
        'approved_at',
        'handle_by',
    ];
}
