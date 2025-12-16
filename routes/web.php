<?php


use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GlobalChatController;
use App\Http\Controllers\Front\ReelController;


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontController::class, 'index'])->name('front.index');

/*
|--------------------------------------------------------------------------
| CATEGORY & BRAND FLOW
|--------------------------------------------------------------------------
*/

// CATEGORY LANDING (Handphone, Kamera, dll)
Route::get(
    '/category/{category:slug}',
    [FrontController::class, 'category']
)->name('front.category');

// FILTER BRAND DI DALAM CATEGORY
Route::get(
    '/category/{category:slug}/brand/{brand:slug}',
    [FrontController::class, 'categoryByBrand']
)->name('front.category.brand');

/*
|--------------------------------------------------------------------------
| PRODUCT
|--------------------------------------------------------------------------
*/
Route::get(
    '/details/{product:slug}',
    [FrontController::class, 'details']
)->name('front.details');

/*
|--------------------------------------------------------------------------
| BOOKING FLOW
|--------------------------------------------------------------------------
*/
Route::get(
    '/booking/{product:slug}',
    [FrontController::class, 'booking']
)->name('front.booking');

Route::post(
    '/booking/{product:slug}/save',
    [FrontController::class, 'booking_save']
)->name('front.booking_save');

Route::get(
    '/checkout/{product:slug}/payment',
    [FrontController::class, 'checkout']
)->name('front.checkout');

Route::post(
    '/checkout/finish',
    [FrontController::class, 'checkout_store']
)->name('front.checkout.store');

Route::get(
    '/success-booking/{transaction}',
    [FrontController::class, 'success_booking']
)->name('front.success.booking');

/*
|--------------------------------------------------------------------------
| MY BOOKING / TRANSACTION
|--------------------------------------------------------------------------
*/
Route::get(
    '/transactions',
    [FrontController::class, 'transactions']
)->name('front.transactions');

Route::post(
    '/transactions/details',
    [FrontController::class, 'transaction_details']
)->name('front.transaction.details');

Route::get(
    '/my-booking/{transaction}',
    [FrontController::class, 'transaction_detail']
)->name('front.transaction.detail');


// routes/web.php web gabut
Route::get('/global-chat', [GlobalChatController::class, 'index'])
    ->name('global.chat');

// reeels
Route::get('/reels', [ReelController::class, 'index'])
    ->name('front.reels');


























