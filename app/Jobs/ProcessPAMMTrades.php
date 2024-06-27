<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\AutoTrading;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use App\Models\AutoTradingLog;
use App\Models\TradingAccount;
use App\Services\CTraderService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\RunningNumberService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\ChangeTraderBalanceType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPAMMTrades implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pamm;

    /**
     * Create a new job instance.
     *
     * @param Setting $pamm
     * @return void
     */
    public function __construct(Setting $pamm)
    {
        $this->pamm = $pamm;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $autoTradingRecords = AutoTrading::where('status', 'ongoing')->get();
        $cTraderService = new CTraderService();

        foreach ($autoTradingRecords as $record) {
            try {
                $oldPamm = $record->cumulative_pamm_return;
                $oldAmount = $record->cumulative_amount;
                $oldEarning = $record->cumulative_earning;

                $updatedValue = $this->pamm->value / 100; // Convert percentage to decimal

                $pamm_return = abs($this->pamm->value);
                $earning = $record->cumulative_earning;
                $amount = round((abs($updatedValue) * $earning), 2);

                $meta_login = $record->meta_login;
                $comment = "PAMM Return Balance";

                $tradeType = $updatedValue >= 0 ? ChangeTraderBalanceType::DEPOSIT : ChangeTraderBalanceType::WITHDRAW;
                
                if ($tradeType === ChangeTraderBalanceType::DEPOSIT) {
                    $record->cumulative_pamm_return += $pamm_return;
                    $record->cumulative_amount += $amount;
                    $record->cumulative_earning += $amount;
                } else {
                    if ($earning - $amount < 0.01) {
                        $amount = $earning;
                        $record->status = 'transfer';
                    }    

                    $record->cumulative_pamm_return -= $pamm_return;
                    $record->cumulative_amount -= $amount;
                    $record->cumulative_earning -= $amount;
                }

                $trade = $cTraderService->createTrade($meta_login, $amount, $comment, $tradeType);

                $record->save();

                // Create transaction record
                $transactionType = $tradeType == ChangeTraderBalanceType::DEPOSIT ? 'deposit' : 'withdrawal';
                Transaction::create([
                    'user_id' => $record->user_id,
                    'category' => 'trading_account',
                    'transaction_type' => $transactionType,
                    'from_meta_login' => $tradeType == ChangeTraderBalanceType::DEPOSIT ? null : $meta_login,
                    'to_meta_login' => $tradeType == ChangeTraderBalanceType::DEPOSIT ? $meta_login : null,
                    'ticket' => $trade->getTicket(),
                    'transaction_number' => RunningNumberService::getID('transaction'),
                    'amount' => $amount,
                    'transaction_amount' => $amount,
                    'status' => 'success',
                    'remarks' => 'AutoTrading',
                    'handle_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // Log the successful update
                AutoTradingLog::create([
                    'auto_trading_id' => $record->id,
                    'old_pamm' => $oldPamm,
                    'new_pamm' => $record->cumulative_pamm_return,
                    'old_amount' => $oldAmount,
                    'new_amount' => $record->cumulative_amount,
                    'old_earning' => $oldEarning,
                    'new_earning' => $record->cumulative_earning,
                    'status' => 'success',
                    'remarks' => 'AutoTrading job executed successfully.',
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error processing PAMM trade: ' . $e->getMessage());

                AutoTradingLog::create([
                    'auto_trading_id' => $record->id,
                    'old_pamm' => $oldPamm,
                    'new_pamm' => $oldPamm + $pamm_return,
                    'old_amount' => $oldAmount,
                    'new_amount' => $oldAmount + $amount,
                    'old_earning' => $oldEarning,
                    'new_earning' => $oldEarning + $amount,
                    'status' => 'failed',
                    'remarks' => 'Failed to execute AutoTrading job: ' . $e->getMessage(),
                ]);
            }
        }
    }
}
