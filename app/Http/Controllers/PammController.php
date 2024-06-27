<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\AutoTrading;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SettingHistory;
use App\Models\TradingAccount;
use Illuminate\Support\Carbon;
use App\Jobs\ProcessPAMMTrades;
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

        // Dispatch ProcessPAMMTrades job automatically
        ProcessPAMMTrades::dispatch($pamm);
    
        return redirect()->back()->with('toast', [
            'title' => trans('public.pamm_update_success_title'),
            'type' => 'success'
        ]);
    }
}
