<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\AutoTrading;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SettingHistory;
use Illuminate\Support\Carbon;
use App\Services\CTraderService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\RunningNumberService;
use App\Http\Requests\UpdatePammRequest;
use App\Services\ChangeTraderBalanceType;

class PammController extends Controller
{
    public function pamm_return()
    {
        $pamm = Setting::where('slug', 'pamm-return')->first();

        return Inertia::render('Pamm/PammReturn', [
            'pamm' => $pamm,
        ]);
    }

    public function pamm_history()
    {
        $pamm = Setting::where('slug', 'pamm-return')->first();

        $histories = SettingHistory::where('setting_id', $pamm->id)->latest()->paginate(10);

        return response()->json($histories);
    }

    public function update_pamm(UpdatePammRequest $request)
    {
        $pamm = Setting::where('slug', 'pamm-return')->first();
    
        $oldValue = $pamm->value;
    
        // Remove leading + sign from the input value
        $newValue = ltrim($request->pamm, '+');
    
        $pamm->update([
            'value' => $newValue,
        ]);
    
        // Create a record in SettingHistory
        SettingHistory::create([
            'setting_id' => $pamm->id,
            'user_id' => Auth::id(),
            'setting_old_value' => $oldValue,
            'setting_new_value' => $newValue,
        ]);

        // Create PAMM trade based on the updated value
        $this->createPAMMTradesForAutoTrading($pamm);
    
        return redirect()->back()->with('toast', [
            'title' => trans('public.pamm_update_success_title'),
            'type' => 'success'
        ]);
    }

    private function createPAMMTradesForAutoTrading(Setting $pamm)
    {
    
        $autoTradingRecords = AutoTrading::where('status', 'ongoing')->get();
    
        // Initialize CTraderService
        $cTraderService = new CTraderService();
    
        foreach ($autoTradingRecords as $record) {
            try {
                $investmentAmount = $record->investment_amount;
                $updatedValue = $pamm->value / 100; // Convert percentage to decimal
    
                $pamm_return = abs($pamm->value);
                // Calculate the amount based on percentage and round up to 2 decimal
                $amount = round((abs($updatedValue) * $investmentAmount), 2);

                // Assuming you have the user's meta login information and necessary data
                $meta_login = $record->meta_login;
                $comment = "PAMM Return Balance";
    
                // Determine trade type based on updatedValue
                $tradeType = $updatedValue >= 0 ? ChangeTraderBalanceType::DEPOSIT : ChangeTraderBalanceType::WITHDRAW;
    
                // Create the trade
                $trade = $cTraderService->createTrade($meta_login, $amount, $comment, $tradeType);
    
                // Update cumulative_pamm_return and cumulative_amount based on tradeType
                if ($tradeType === ChangeTraderBalanceType::DEPOSIT) {
                    $record->cumulative_pamm_return += $pamm_return;
                    $record->cumulative_amount += $amount;
                } else {
                    $record->cumulative_pamm_return -= $pamm_return;
                    $record->cumulative_amount -= $amount;
                }
    
                // Save the updated record
                $record->save();

                // Create a transaction record
                $this->createTransactionRecord($record->user_id, $meta_login, $amount, $tradeType, $trade->getTicket());
    
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    private function createTransactionRecord($user_id, $meta_login, $amount, $tradeType, $ticket)
    {
        try {
            // Determine transaction type text
            $transactionType = $tradeType == ChangeTraderBalanceType::DEPOSIT ? 'deposit' : 'withdrawal';

            // Create transaction record
            Transaction::create([
                'user_id' => $user_id,
                'category' => 'trading_account',
                'transaction_type' => $transactionType,
                'from_meta_login' => null,
                'to_meta_login' => $meta_login,
                'ticket' => $ticket,
                'transaction_number' => RunningNumberService::getID('transaction'),
                'amount' => $amount,
                'transaction_amount' => $amount,
                'status' => 'success',
                'remarks' => 'AutoTrading',
                'handle_by' => Auth::id(),
                'approved_at' => now(),
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
