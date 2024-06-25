<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClient = User::whereNull('deleted_at')->where('role','user')->count();
        $totalDeposit = Transaction::where('category', 'wallet')->where('transaction_type', 'deposit')->where('status', 'success')->sum('transaction_amount');
        $totalWithdrawal = Transaction::where('category', 'wallet')->where('transaction_type', 'withdrawal')->where('status', 'success')->sum('transaction_amount');
        $totalPurchasesEA = Transaction::where('category', 'wallet')->where('transaction_type', 'purchase_robotec')->where('status', 'success')->sum('transaction_amount');
        $totalPammFundIn = Transaction::where('category', 'trading_account')->where('transaction_type', 'fund_in')->where('status', 'success')->sum('transaction_amount');

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
        $currentMonth = Carbon::now()->startOfMonth();
        $totalApprovedResult = Transaction::where('category', 'wallet')
            ->where('transaction_type', 'withdrawal')
            ->where('status', 'success')
            ->where('created_at', '>=', $currentMonth)
            ->count();
    
        $totalRejectedResult = Transaction::where('category', 'wallet')
            ->where('transaction_type', 'withdrawal')
            ->where('status', 'failed')
            ->where('created_at', '>=', $currentMonth)
            ->count();
    
        return Inertia::render('Dashboard/WithdrawalTransaction', [
            'totalApprovedResult' => $totalApprovedResult,
            'totalRejectedResult' => $totalRejectedResult,
        ]);
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
