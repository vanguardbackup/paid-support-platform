<?php

use App\Http\Controllers\Support\CreateSupportRequestController;
use App\Http\Controllers\Support\ShowSupportIndexController;
use App\Http\Controllers\Support\Staff\UpdateSupportRequestController;
use App\Http\Controllers\Support\ViewRequestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/support', [ShowSupportIndexController::class, '__invoke'])->name('support.index');
    Route::get('/support/create', [CreateSupportRequestController::class, 'show'])->name('support.create');
    Route::post('/support', [CreateSupportRequestController::class, 'store'])->name('support.store');
    Route::get('/support/{supportRequest}', [ViewRequestController::class, '__invoke'])->name('support.show');
    Route::post('/support/{supportRequest}', [UpdateSupportRequestController::class, '__invoke'])->name('support.update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
