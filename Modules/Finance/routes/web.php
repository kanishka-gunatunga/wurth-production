<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\UserController;
use Modules\Finance\Http\Controllers\InquiriesController;
use Modules\Finance\Http\Controllers\CollectionsController;
use Modules\Finance\Http\Middleware\FinanceAuthenticated;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('finance')->group(function() {
    Route::match(['get', 'post'],'/', [UserController::class, 'dashboard'])->middleware(FinanceAuthenticated::class);
    Route::match(['get', 'post'],'/inquiries', [InquiriesController::class, 'inquiries'])->middleware(FinanceAuthenticated::class);

    Route::match(['get', 'post'],'/all-receipts', [CollectionsController::class, 'all_receipts'])->middleware(FinanceAuthenticated::class);
    Route::match(['get', 'post'],'resend-receipt/{id}', [CollectionsController::class, 'resend_receipt']);
    Route::match(['get', 'post'],'remove-advanced-payment/{id}', [CollectionsController::class, 'remove_advanced_payment']);
}); 