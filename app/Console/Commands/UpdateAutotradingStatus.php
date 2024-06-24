<?php

namespace App\Console\Commands;

use App\Models\AutoTrading;
use Illuminate\Console\Command;

class UpdateAutotradingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:autotrading';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update AutoTrading Status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $autoTradingRecords = AutoTrading::where('status', 'ongoing')
                                ->whereDate('matured_at', '<', now())
                                ->get();

        foreach ($autoTradingRecords as $record) {
            $record->update([
                'status' => 'matured'
            ]);
        }
    }
}
