<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClient = User::whereNull('deleted_at')->where('role','user')->count();
        $totalDeposit = Transaction::where('transaction_type', 'deposit')->where('status', 'Success')->sum('transaction_amount');
        $totalWithdrawal = Transaction::where('transaction_type', 'withdrawal')->where('status', 'Approved')->sum('transaction_amount');
        $totalPurchasesEA = Transaction::where('transaction_type', 'robotec_purchase')->where('status', 'Success')->sum('transaction_amount');
        $totalPammFundIn = Transaction::where('transaction_type', 'pamm_funding')->where('status', 'Success')->sum('transaction_amount');

        return Inertia::render('Dashboard', [
            'totalClient' => $totalClient,
            'totalDeposit' => $totalDeposit,
            'totalWithdrawal' => $totalWithdrawal,
            'totalPurchasesEA' => $totalPurchasesEA,
            'totalPammFundIn' => $totalPammFundIn,
        ]);
    }

    public function deposit_transactions()
    {
        return Inertia::render('Dashboard/DepositTransaction');
    }

    public function withdrawal_transactions()
    {
        return Inertia::render('Dashboard/WithdrawalTransaction');
    }

    public function robotec_purchase()
    {
        return Inertia::render('Dashboard/RobotecPurchases');
    }

    public function pamm_transactions()
    {
        return Inertia::render('Dashboard/PammTransaction');
    }
}
