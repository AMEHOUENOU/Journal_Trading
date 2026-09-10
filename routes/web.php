<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\DailyNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StrategyInstrumentController;
use App\Http\Controllers\TradingScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EconomicCalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('accounts', AccountController::class);

    Route::get('accounts/{account}/trades', [TradeController::class, 'index'])->name('accounts.trades.index');
    Route::get('accounts/{account}/trades/create', [TradeController::class, 'create'])->name('accounts.trades.create');
    Route::post('accounts/{account}/trades', [TradeController::class, 'store'])->name('accounts.trades.store');
    Route::get('accounts/{account}/trades/export', [TradeController::class, 'export'])->name('accounts.trades.export');
    Route::post('accounts/{account}/trades/import', [TradeController::class, 'import'])->name('accounts.trades.import');

    Route::get('trades/{trade}', [TradeController::class, 'show'])->name('trades.show');
    Route::get('trades/{trade}/edit', [TradeController::class, 'edit'])->name('trades.edit');
    Route::put('trades/{trade}', [TradeController::class, 'update'])->name('trades.update');
    Route::delete('trades/{trade}', [TradeController::class, 'destroy'])->name('trades.destroy');

    Route::resource('tags', TagController::class);

    Route::post('tags/{tag}/instruments', [StrategyInstrumentController::class, 'store'])->name('tags.instruments.store');
    Route::put('tags/{tag}/instruments/{instrument}', [StrategyInstrumentController::class, 'update'])->name('tags.instruments.update');
    Route::delete('tags/{tag}/instruments/{instrument}', [StrategyInstrumentController::class, 'destroy'])->name('tags.instruments.destroy');

    Route::get('trading-schedule', [TradingScheduleController::class, 'index'])->name('schedule.index');

    Route::resource('daily-notes', DailyNoteController::class)->except(['show']);

    Route::get('economic-calendar', [EconomicCalendarController::class, 'index'])->name('calendar.index');
});

require __DIR__.'/auth.php';