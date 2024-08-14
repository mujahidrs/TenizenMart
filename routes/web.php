<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index']);
Route::get('/', [LandingPageController::class, 'search']);

Route::middleware('auth')->group(function(){
    Route::post('/addToCart/{id}', [LandingPageController::class, 'store'])->name('addToCart');
    Route::put('/updateQuantity/{id}', [LandingPageController::class, 'update'])->name('updateQuantity');
    Route::get('/checkout', [LandingPageController::class, 'checkout'])->name('checkout');
    Route::get('/destroy/{cart_id}', [LandingPageController::class, 'destroy'])->name('destroyCart');
    Route::get('/complete/{invoice_number}', [HomeController::class, 'completeTransaction'])->name('completeTransaction');
    Route::post('/topUpSaldo', [LandingPageController::class, 'topUpSaldo'])->name('topUpSaldo');
    Route::post('/tarikTunai', [LandingPageController::class, 'tarikTunai'])->name('tarikTunai');
    Route::get('/acceptWalletRequest/{id}', [HomeController::class, 'acceptWalletRequest'])->name('acceptWalletRequest');
    Route::get('/rejectWalletRequest/{id}', [HomeController::class, 'rejectWalletRequest'])->name('rejectWalletRequest');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/biodata', [App\Http\Controllers\BiodataController::class, 'index']);