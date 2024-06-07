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

    public function WalletAdjustment(WalletAdjustmentRequest $request)
    {
        $clientId = $request->input('id');
        $walletType = $request->input('type');
        $amount = (float) $request->input('amount');
        $isDeduction = $request->input('isDeduction'); // Retrieve the boolean value
        
        // Retrieve the wallet
        $wallet = Wallet::where('user_id', $clientId)->where('type', $walletType)->first();
    
        // Check if it's a deduction and the amount exceeds the balance
        if ($isDeduction && $amount > $wallet->balance) {
            throw ValidationException::withMessages(['amount' => 'The amount for deduction exceeds the available balance']);
        }
    
        // Adjust the amount based on isDeduction
        if ($isDeduction) {
            // If it's a deduction, ensure the amount is negative
            $amount = -abs($amount);
            $actionMessage = 'deducted';
        } else {
            // If it's an addition, ensure the amount is positive
            $amount = abs($amount);
            $actionMessage = 'added';
        }
    
        // Create a transaction record
        $transaction = Transaction::create([
            'user_id' => $clientId,
            'category' => 'wallet',
            'transaction_type' => 'adjustment',
            'transaction_number' => RunningNumberService::getID('adjustment'),
            'amount' => abs($amount),
            'transaction_amount' => abs($amount),
            'old_wallet_amount' => $wallet->balance,
            'new_wallet_amount' => $wallet->balance + $amount,
            'from_wallet_id' => $isDeduction ? $wallet->id :  null,
            'to_wallet_id' => $isDeduction ?  null : $wallet->id,
            'from_wallet_address' => $isDeduction ? $wallet->wallet_address : null,
            'to_wallet_address' => $isDeduction ? null : $wallet->wallet_address,
            'status' => 'Success',
        ]);

        // Update the wallet balance
        $wallet->update([
            'balance' => $wallet->balance + $amount,
        ]);
        
        // Generate the success message dynamically
        $successMessage = "has been $actionMessage successfully.";
        
        return redirect()->back()->with('toast', [
            'title' => 'Wallet Adjustment Successful',
            'message' => '$ ' . number_format(abs($amount), 2) . ' has been ' . $successMessage,
            'type' => 'success'
        ]);
    }

    public function adjustment_history(Request $request)
    {
        $clientId = $request->input('client');
        $walletType = $request->input('type');

        $wallet = Wallet::where('user_id', $clientId)->where('type', $walletType)->first();

        $histories = Transaction::where('user_id', $clientId)
            ->where('transaction_type', 'adjustment')
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
            ->whereNotNull('from_wallet_address')
            ->whereNull('approved_at')
            ->where('status', 'Pending');
    
        
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
            ->whereNotNull('from_wallet_address')
            ->whereNull('approved_at')
            ->where('status', 'Pending')
            ->firstOrFail();

        // Verify wallet address and transaction number
        if ($request->wallet_address !== $withdrawalRequest->from_wallet_address) {
            throw ValidationException::withMessages(['wallet_address' => 'The wallet address is not for this withdrawal request']);
        }

        if ($request->transaction_number !== $withdrawalRequest->transaction_number) {
            throw ValidationException::withMessages(['transaction_number' => 'The transaction number is not for this withdrawal request']);
        }

        // Check if the transaction amount exceeds the wallet balance
        if ($withdrawalRequest->transaction_amount > $withdrawalRequest->from_wallet->balance) {
            throw ValidationException::withMessages(['transaction_amount' => 'The withdrawal amount exceeds the wallet balance']);
        }

        // Update withdrawal request status and approval timestamp
        $withdrawalRequest->update([
            'old_wallet_amount' => $withdrawalRequest->from_wallet->balance,
            'new_wallet_amount' => $withdrawalRequest->from_wallet->balance - $withdrawalRequest->transaction_amount,
            'status' => 'Success',
            'approved_at' => now(),
        ]);

        // Update wallet balance
        $withdrawalRequest->from_wallet->decrement('balance', $withdrawalRequest->transaction_amount);

        return redirect()->back()->with('toast', [
            'title' => 'Withdrawal Request Approved!',
            'message' => 'Withdrawal request of $ '. $withdrawalRequest->transaction_amount . ' has been approved successfully.',
            'type' => 'success'
        ]);
    }

    public function reject_withdrawal_request(Request $request)
    {
        $request->validate([
            'remarks' => ['required'],
        ]);

        $withdrawalRequest = Transaction::where('id', $request->id)
            ->where('transaction_type', 'withdrawal')
            ->whereNotNull('from_wallet_id')
            ->whereNotNull('from_wallet_address')
            ->whereNull('approved_at')
            ->where('status', 'Pending')
            ->first();

        // Update withdrawal request status and approval timestamp
        $withdrawalRequest->update([
            'status' => 'Rejected',
            'remarks' => $request->remarks,
        ]);

        // Update wallet balance
        $withdrawalRequest->from_wallet->increment('balance', $withdrawalRequest->transaction_amount);

        return redirect()->back()->with('toast', [
            'title' => 'Withdrawal Request Rejected!',
            'message' => 'Withdrawal request of $ '. $withdrawalRequest->transaction_amount . ' has been rejected successfully.',
            'type' => 'success'
        ]);
    }
}