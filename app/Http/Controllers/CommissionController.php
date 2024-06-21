<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\CommissionPayout;
use Illuminate\Support\Facades\Auth;
use App\Services\RunningNumberService;

class CommissionController extends Controller
{
    public function commission_payout()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $totalCommissionRequest = CommissionPayout::where('status', 'processing')
            ->where('created_at', '>=', $currentMonth)
            ->count();
    
        $totalCommissionHistory = CommissionPayout::where('status', '!=', 'processing')
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
        $query = CommissionPayout::query()->with('upline','downline');
    
        
        // Apply search filter if provided
        $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('upline', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
                // ->orWhereHas('downline', function ($query) use ($request) {
                //     $query->where('name', 'like', '%' . $request->search . '%');
                // });
            })->orWhere('transaction_amount', 'like', '%' . $request->search . '%');
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
        
        // Fetch the commissions
        $commissions = $query->latest()->paginate(10);
    
        // Calculate total amount
        $totalAmount = $commissions->sum('amount');

        // Transform each transaction to include profile_photo for user and upline
        $commissions->getCollection()->transform(function ($commission) {
            $commission->upline->profile_photo = $commission->upline->getFirstMediaUrl('profile_photo');
            $commission->downline->profile_photo = $commission->downline->getFirstMediaUrl('profile_photo');
            return $commission;
        });
    

        return response()->json([
            'commissions' => $commissions,
            'totalAmount' => $totalAmount,
            'totalPending' => $totalPending,
            'totalHistory' => $totalHistory,
        ]);
    }

    public function approve_commission(Request $request)
    {
        $commissionIds = $request->input('ids');
    
        foreach ($commissionIds as $commissionId) {
            $commission = CommissionPayout::findOrFail($commissionId);
            $commissionWallet = Wallet::where('user_id', $commission->upline_id)->where('type', 'commission_wallet')->first();

            $transaction = Transaction::create([
                'user_id' => $commission->upline_id,
                'category' => 'wallet',
                'transaction_type' => 'commission',
                'to_wallet_id' => $commissionWallet->id,
                'transaction_number' => RunningNumberService::getID('transaction'),
                'amount' => $commission->amount,
                'transaction_amount' => $commission->amount,
                'old_wallet_amount' => $commissionWallet->balance,
                'new_wallet_amount' => $commissionWallet->balance + $commission->amount,
                'status' => 'success',
                'approved_at' => Carbon::now(),
                'handle_by' => Auth::id(),
            ]);
    
            // Update the transaction's status and new_wallet_amount
            $commission->update([
                'transaction_id' => $transaction->id,
                'status' => 'success',
                'approved_at' => Carbon::now(),
                'handle_by' => Auth::id(),
            ]);
    
            // Update the commission wallet's balance directly
            $commissionWallet->balance += $commission->amount;
            $commissionWallet->save();
        }
    
        return redirect()->back()->with('toast', [
            'title' => trans('public.commission_approve_title'),
            'type' => 'success'
        ]);
    }
            
}

