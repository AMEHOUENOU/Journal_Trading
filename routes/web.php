<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


use App\Http\Controllers\AccountController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\DailyNoteController;
use App\Http\Controllers\DashboardController;

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

    Route::resource('tags', TagController::class)->except(['show']);
    Route::resource('daily-notes', DailyNoteController::class)->except(['show']);
});


require __DIR__.'/auth.php';
