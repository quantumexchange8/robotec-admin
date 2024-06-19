<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveWithdrawalRequest;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Services\RunningNumberService;
use App\Http\Requests\WalletAdjustmentRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function wallet_adjustment(Request $request)
    {
        return Inertia::render('WalletAdjustment/WalletAdjustment');
    }

    public function wallet(Request $request)
    {
        $clientId = $request->input('client');
        $walletType = $request->input('type');

        $wallet = Wallet::where('user_id', $clientId)->where('type', $walletType)->first();

        return response()->json($wallet);
    }

    public function walletAdjustment(WalletAdjustmentRequest $request)
    {
        $clientId = $request->input('id');
        $walletType = $request->input('type');
        $amount = (float) $request->input('amount');
        $isDeduction = $request->input('isDeduction'); // Retrieve the boolean value
        
        // Retrieve the client and wallet
        $client = User::where('id', $clientId)->where('status', 'Active')->first();
        $wallet = Wallet::where('user_id', $clientId)->where('type', $walletType)->first();
    
        // Check if it's a deduction and the amount exceeds the balance
        if ($isDeduction && $amount > $wallet->balance) {
            throw ValidationException::withMessages(['amount' => trans('public.deduction_amount_exceed_balance')]);
        }
    
        // Adjust the amount based on isDeduction
        if ($isDeduction) {
            // If it's a deduction, ensure the amount is negative
            $amount = -abs($amount);
            $actionMessage = trans('public.deducted');
        } else {
            // If it's an addition, ensure the amount is positive
            $amount = abs($amount);
            $actionMessage = trans('public.added');
        }
    
        // Create a transaction record
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'category' => 'wallet',
            'transaction_type' => 'adjustment',
            'transaction_number' => RunningNumberService::getID('adjustment'),
            'amount' => abs($amount),
            'transaction_amount' => abs($amount),
            'old_wallet_amount' => $wallet->balance,
            'new_wallet_amount' => $wallet->balance + $amount,
            'from_wallet_id' => $isDeduction ? $wallet->id :  null,
            'to_wallet_id' => $isDeduction ?  null : $wallet->id,
            'from_wallet_address' => $isDeduction ? $client->usdt_address : null,
            'to_wallet_address' => $isDeduction ? null : $client->usdt_address,
            'status' => 'success',
        ]);

        // Update the wallet balance
        $wallet->update([
            'balance' => $wallet->balance + $amount,
        ]);
                
        return redirect()->back()->with('toast', [
            'title' => trans('public.wallet_adjustment_success_title'),
            'message' => trans('public.wallet_adjustment_success_message', ['amount' => number_format(abs($amount), 2), 'actionMessage' => $actionMessage]),
            'type' => 'success'
        ]);
    }

    public function adjustment_history(Request $request)
    {
        $clientId = $request->input('client');
        $walletType = $request->input('type');

        $wallet = Wallet::where('user_id', $clientId)->where('type', $walletType)->first();

        $histories = Transaction::where('transaction_type', 'adjustment')
            ->where(function ($query) use ($wallet) {
                $query->where('from_wallet_id', $wallet->id)
                    ->orWhere('to_wallet_id', $wallet->id);
            })
            ->latest()
            ->paginate(10);
    
        return response()->json($histories);
    }

    public function withdrawal_request(Request $request)
    {
        return Inertia::render('WithdrawalRequest/WithdrawalRequest');
    }

    public function withdrawal_request_data(Request $request)
    {
        // Start building the query
        $query = Transaction::query()
            ->with('user','from_wallet')
            ->where('transaction_type', 'withdrawal')
            ->whereNotNull('from_wallet_id')
            ->whereNull('approved_at')
            ->where('status', 'processing');
    
        
        // Apply search filter if provided
        $query->when($request->search, function ($query) use ($request) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%');
                })->orWhere('transaction_amount', 'like', '%'.$request->search.'%');
            });
        });
    
        // Apply date range filter if provided
        $query->when($request->filled('date'), function ($query) use ($request) {
            [$start_date, $end_date] = explode(' - ', $request->input('date'));
            $query->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay()
            ]);
        });
        

        // Fetch the transactions
        $transactions = $query->latest()->paginate(10);

        // Calculate total amount
        $totalAmount = $transactions->sum('transaction_amount');

        // Transform each transaction to include profile_photo for user and upline
        $transactions->getCollection()->transform(function ($transaction) {
            $transaction->user->profile_photo = $transaction->user->getFirstMediaUrl('profile_photo');
            return $transaction;
        });
    
        // Return the paginated results along with total amount
        return response()->json([
            'transactions' => $transactions,
            'totalAmount' => $totalAmount
        ]);
        
    }

    public function approve_withdrawal_request(ApproveWithdrawalRequest $request)
    {
        $withdrawalRequest = Transaction::with('from_wallet')
            ->where('id', $request->id)
            ->where('transaction_type', 'withdrawal')
            ->whereNotNull('from_wallet_id')
            ->whereNull('approved_at')
            ->where('status', 'processing')
            ->firstOrFail();

        // // Verify wallet address and transaction number
        // if ($request->to_wallet_address !== $withdrawalRequest->to_wallet_address) {
        //     throw ValidationException::withMessages(['to_wallet_address' => trans('public.incorrect_usdt_address')]);
        // }

        // // Check if the transaction amount exceeds the wallet balance
        // if ($withdrawalRequest->transaction_amount > $withdrawalRequest->from_wallet->balance) {
        //     throw ValidationException::withMessages(['transaction_amount' => trans('public.withdrawal_amount_exceed_balance')]);
        // }

        // Update withdrawal request status and approval timestamp
        $withdrawalRequest->update([
            'from_wallet_address' => $request->from_wallet_address,
            'txn_hash' => $request->txn_hash,
            'status' => 'success',
            'approved_at' => now(),
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('toast', [
            'title' => trans('public.withdrawal_request_approved_title'),
            'message' => trans('public.withdrawal_request_approved_message', ['amount' => $withdrawalRequest->transaction_amount]),
            'type' => 'success'
        ]);
    }

    public function reject_withdrawal_request(Request $request)
    {
        // $request->validate([
        //     'remarks' => ['required'],
        // ]);

        $withdrawalRequest = Transaction::where('id', $request->id)
            ->where('transaction_type', 'withdrawal')
            ->whereNotNull('from_wallet_id')
            ->whereNull('approved_at')
            ->where('status', 'processing')
            ->first();

        // Update withdrawal request status and approval timestamp
        $withdrawalRequest->update([
            'status' => 'failed',
            'old_wallet_amount' => $withdrawalRequest->from_wallet->balance,
            'new_wallet_amount' => $withdrawalRequest->from_wallet->balance + $withdrawalRequest->amount,
        ]);

        if ($request->remarks) {
            $withdrawalRequest->update([
                'remarks' => $request->remarks,
            ]);    
        }

        // Update wallet balance
        $withdrawalRequest->from_wallet->increment('balance', $withdrawalRequest->amount);

        return redirect()->back()->with('toast', [
            'title' => trans('public.withdrawal_request_rejected_title'),
            'message' => trans('public.withdrawal_request_rejected_message', ['amount' => $withdrawalRequest->amount]),
            'type' => 'success'
        ]);
    }

    public function transasction_data(Request $request)
    {
        // Start building the query
        $query = Transaction::query()
            ->with('user','from_wallet','to_wallet')
            ->where('transaction_type', $request->transaction_type)
            ->whereNotIn('status', ['processing']);

        // If 'status' is provided in the request, add it to the query
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Apply search filter if provided
        $query->when($request->search, function ($query) use ($request) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%');
                })->orWhere('transaction_amount', 'like', '%'.$request->search.'%');
            });
        });
    
        // Apply date range filter if provided
        $query->when($request->filled('date'), function ($query) use ($request) {
            [$start_date, $end_date] = explode(' - ', $request->input('date'));
            $query->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay()
            ]);
        });

        // Fetch the transactions
        $transactions = $query->latest()->paginate(10);

        // Calculate total amount
        $totalAmount = $transactions->sum('transaction_amount');

        // Transform each transaction to include profile_photo for user and upline
        $transactions->getCollection()->transform(function ($transaction) {
            $transaction->user->profile_photo = $transaction->user->getFirstMediaUrl('profile_photo');
            return $transaction;
        });
    
        // Return the paginated results along with total amount
        return response()->json([
            'transactions' => $transactions,
            'totalAmount' => $totalAmount,
        ]);
        
    }


}