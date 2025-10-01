<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\UserController;
use Modules\Finance\Http\Controllers\InquiriesController;
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

Route::prefix('finance')->middleware([FinanceAuthenticated::class])->group(function() {
    Route::match(['get', 'post'],'/', [UserController::class, 'dashboard']);
    Route::match(['get', 'post'],'/inquiries', [InquiriesController::class, 'inquiries'])->name('inquiries');

    // New route for inquiry details
    Route::get('/inquiry-details/{id}', [InquiriesController::class, 'details'])->name('inquiry.details');
}); 