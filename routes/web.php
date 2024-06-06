<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PammController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\TransactionController;

Route::get('locale/{locale}', function ($locale) {
    App::setLocale($locale);
    Session::put("locale", $locale);

    return redirect()->back();
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * ==============================
     *           Network
     * ==============================
     */
    Route::prefix('network')->group(function () {
        Route::get('/client_network', [NetworkController::class, 'client_network'])->name('network.client_network');
    });

    /**
     * ==============================
     *          Member
     * ==============================
     */
    Route::prefix('member')->group(function () {
        Route::get('/client_listing', [MemberController::class, 'client_listing'])->name('member.client_listing');
        Route::get('/client_data', [MemberController::class, 'client_data'])->name('member.client_data');
        Route::post('/addClient', [MemberController::class, 'addClient'])->name('member.addClient');
        Route::post('/update_client', [MemberController::class, 'update_client'])->name('member.update_client');
        Route::delete('/delete_client', [MemberController::class, 'delete_client'])->name('member.delete_client');
        Route::get('/getAllClients', [MemberController::class, 'getAllClients'])->name('member.getAllClients');
    });

    /**
     * ==============================
     *      Commission Payout
     * ==============================
     */
    Route::prefix('commission')->group(function () {
        Route::get('/commission_payout', [CommissionController::class, 'commission_payout'])->name('commission.commission_payout');
        Route::get('/commission_request_data', [CommissionController::class, 'commission_request_data'])->name('commission.commission_request_data');
        Route::post('/approve_commission', [CommissionController::class, 'approve_commission'])->name('commission.approve_commission');
    });

    /**
     * ==============================
     *          Pamm
     * ==============================
     */
    Route::prefix('pamm')->group(function () {
        Route::get('/pamm_return', [PammController::class, 'pamm_return'])->name('pamm.pamm_return');
        Route::get('/pamm_history', [PammController::class, 'pamm_history'])->name('pamm.pamm_history');
        Route::post('/update_pamm', [PammController::class, 'update_pamm'])->name('pamm.update_pamm');
    });

    /**
     * ==============================
     *          Transaction
     * ==============================
     */
    Route::prefix('transaction')->group(function () {
        Route::get('/wallet_adjustment', [TransactionController::class, 'wallet_adjustment'])->name('transaction.wallet_adjustment');
        Route::get('/wallet', [TransactionController::class, 'wallet'])->name('transaction.wallet');
        Route::get('/adjustment_history', [TransactionController::class, 'adjustment_history'])->name('transaction.adjustment_history');
        Route::post('/WalletAdjustment', [TransactionController::class, 'WalletAdjustment'])->name('transaction.WalletAdjustment');

    });

    /**
     * ==============================
     *           Profile
     * ==============================
     */
    Route::prefix('profile')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
