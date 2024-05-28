<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function withdrawal_request(Request $request)
    {
        return Inertia::render('WithdrawalRequest/WithdrawalRequest');
    }
}
