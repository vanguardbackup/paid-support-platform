<?php

use App\Http\Controllers\Support\DeductTimeController;
use App\Http\Controllers\Support\SupportTimePurchaseController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Webhooks\MollieWebhookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root route - show marketing view for guests, redirect to home for authenticated users
Route::get('/', function () {
    return Auth::check() ? redirect('/home') : view('index');
})->name('root');

// Authentication Routes
Auth::routes();

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {

    Route::get('home', [HomeController::class, 'index'])->name('home');
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // Support Time Purchase Routes
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('purchase', [SupportTimePurchaseController::class, 'showPurchaseForm'])->name('purchase');
        Route::post('purchase', [SupportTimePurchaseController::class, 'initiatePurchase'])->name('purchase.initiate');
        Route::get('payment/callback', [SupportTimePurchaseController::class, 'handlePaymentCallback'])->name('payment.callback');

        Route::middleware('admin')->group(function () {
            Route::get('list', [DeductTimeController::class, 'index'])->name('deduct.list');
            Route::post('list', [DeductTimeController::class, 'deductTime'])->name('deduct-time.post');
        });
    });
});

// Webhook Routes
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('mollie', [MollieWebhookController::class, 'handleWebhookNotification'])->name('mollie');
});

Route::view('terms', 'terms')->name('terms');
