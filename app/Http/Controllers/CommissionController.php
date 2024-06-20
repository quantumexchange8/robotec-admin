<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function commission_payout()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $totalCommissionRequest = Transaction::where('transaction_type', 'commission')
            ->where('status', 'processing')
            ->where('created_at', '>=', $currentMonth)
            ->count();
    
        $totalCommissionHistory = Transaction::where('transaction_type', 'commission')
            ->where('status', '!=', 'processing')
            ->where('created_at', '>=', $currentMonth)
            ->count();
            
        return Inertia::render('CommissionPayout/CommissionPayout', [
            'totalCommissionRequest' => $totalCommissionRequest,
            'totalCommissionHistory' => $totalCommissionHistory,
        ]);
    }

    public function commission_request_data(Request $request)
    {
        // Start building the query
        $query = Transaction::query()->with('user','to_wallet.user')->where('transaction_type', 'commission');
    
        
        // Apply search filter if provided
        $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%');
                })->orWhereHas('to_wallet.user', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%');
                });
            })->orWhere('transaction_amount', 'like', '%'.$request->search.'%');
        });
            
        // Apply date range filter if provided
        $query->when($request->filled('date'), function ($query) use ($request) {
            [$start_date, $end_date] = explode(' - ', $request->input('date'));
            $query->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay()
            ]);
        });
        
        // Clone the query for totalPending
        $totalPendingQuery = clone $query;
        $totalPending = $totalPendingQuery->where('status', 'processing')->count();

        // Clone the query for totalHistory
        $totalHistoryQuery = clone $query;
        $totalHistory = $totalHistoryQuery->where('status', '!=', 'processing')->count();
        
        // Apply type filter
        $query->when($request->type == 'Pending', function ($query) {
            $query->where('status', 'processing');
        })->when($request->type == 'History', function ($query) {
            $query->where('status', '!=', 'processing');
        });
        
        // Fetch the transactions
        $transactions = $query->latest()->paginate(10);
    
        // Calculate total amount
        $totalAmount = $transactions->sum('transaction_amount');

        // Transform each transaction to include profile_photo for user and upline
        $transactions->getCollection()->transform(function ($transaction) {
            $transaction->user->profile_photo = $transaction->user->getFirstMediaUrl('profile_photo');
            $transaction->to_wallet->user->profile_photo = $transaction->to_wallet->user->getFirstMediaUrl('profile_photo');
            return $transaction;
        });
    

        return response()->json([
            'transactions' => $transactions,
            'totalAmount' => $totalAmount,
            'totalPending' => $totalPending,
            'totalHistory' => $totalHistory,
        ]);
    }

    public function approve_commission(Request $request)
    {
        $commissionIds = $request->input('ids');
    
        foreach ($commissionIds as $commissionId) {
            $transaction = Transaction::findOrFail($commissionId);
            $commissionWallet = Wallet::findOrFail($transaction->to_wallet_id);
    
            // Update the transaction's status and new_wallet_amount
            $transaction->update([
                'status' => 'success',
                'old_wallet_amount' => $commissionWallet->balance,
                'new_wallet_amount' => $commissionWallet->balance + $transaction->amount,
                'approved_at' => Carbon::now(),
            ]);
    
            // Update the commission wallet's balance directly
            $commissionWallet->balance += $transaction->amount;
            $commissionWallet->save();
        }
    
        return redirect()->back()->with('toast', [
            'title' => trans('public.commission_approve_title'),
            'type' => 'success'
        ]);
    }
            
}

