<?php

namespace App\Http\Controllers;

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
}
