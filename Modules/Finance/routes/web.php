<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\UserController;
use Modules\Finance\Http\Controllers\InquiriesController;
use Modules\Finance\Http\Controllers\CollectionsController;
use Modules\Finance\Http\Middleware\FinanceAuthenticated;
use Modules\Finance\Http\Controllers\AdvancedPaymentsController;
use Modules\Finance\Http\Controllers\CashDepositsController;
use Modules\Finance\Http\Controllers\ChequeDepositsController;
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

Route::prefix('finance')->middleware([FinanceAuthenticated::class])->group(function () {
    Route::match(['get', 'post'], '/', [UserController::class, 'dashboard']);
    Route::match(['get', 'post'], '/inquiries', [InquiriesController::class, 'inquiries'])->name('inquiries');

    Route::get('/inquiry-details/{id}', [InquiriesController::class, 'details'])->name('inquiry.details');
    Route::post('/inquiries/approve/{id}', [InquiriesController::class, 'approve'])->name('inquiries.approve');
    Route::post('/inquiries/reject/{id}', [InquiriesController::class, 'reject'])->name('inquiries.reject');

    Route::get('/advanced-payments', [AdvancedPaymentsController::class, 'index'])
        ->name('advanced_payments.index');
    Route::get('/advance-payments-details/{id}', [AdvancedPaymentsController::class, 'show'])
        ->name('advanced_payments.show');

    Route::get('/cash-deposits', [CashDepositsController::class, 'index'])->name('cash_deposits.index');
    Route::get('/cash-deposits/{id}', [CashDepositsController::class, 'show'])->name('cash_deposits.show');

    Route::match(['get', 'post'], '/all-receipts', [CollectionsController::class, 'all_receipts'])->middleware(FinanceAuthenticated::class);
    Route::match(['get', 'post'], 'resend-receipt/{id}', [CollectionsController::class, 'resend_receipt']);
    Route::match(['get', 'post'], 'remove-advanced-payment/{id}', [CollectionsController::class, 'remove_advanced_payment']);
    Route::get('/cash-deposits/download/{id}', [CashDepositsController::class, 'downloadAttachment'])
        ->name('cash_deposits.download');

    Route::get('/cheque-deposits', [ChequeDepositsController::class, 'index'])->name('cheque_deposits.index');
    Route::get('/cheque-deposits/download/{id}', [ChequeDepositsController::class, 'downloadAttachment'])->name('cheque_deposits.download');
});
