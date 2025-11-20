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
use Modules\Finance\Http\Controllers\FinanceCashController;
use Modules\Finance\Http\Controllers\FinanceChequeController;
use Modules\Finance\Http\Controllers\WriteOffController;
use Modules\Finance\Http\Controllers\SetOffController;
use Modules\Finance\Http\Controllers\FundTransferController;
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
    Route::post('/inquiries/search', [InquiriesController::class, 'search'])->name('inquiries.search');
    Route::post('/inquiries/filter', [InquiriesController::class, 'filter'])->name('inquiries.filter');

    Route::get('/advanced-payments', [AdvancedPaymentsController::class, 'index'])
        ->name('advanced_payments.index');
    Route::get('/advance-payments-details/{id}', [AdvancedPaymentsController::class, 'show'])
        ->name('advanced_payments.show');
    Route::post('/advanced-payments/search', [AdvancedPaymentsController::class, 'search'])
        ->name('advanced_payments.search');

    Route::get('/cash-deposits', [CashDepositsController::class, 'index'])->name('cash_deposits.index');
    Route::get('/cash-deposits/{id}', [CashDepositsController::class, 'show'])->name('cash_deposits.show');

    Route::match(['get', 'post'], '/all-receipts', [CollectionsController::class, 'all_receipts'])->middleware(FinanceAuthenticated::class);
    Route::match(['get', 'post'], 'resend-receipt/{id}', [CollectionsController::class, 'resend_receipt']);
    Route::match(['get', 'post'], 'remove-advanced-payment/{id}', [CollectionsController::class, 'remove_advanced_payment']);

    Route::get('/cash-deposits/download/{id}', [CashDepositsController::class, 'downloadAttachment'])
        ->name('cash_deposits.download');
    Route::post('/cash-deposits/update-status/{id}', [CashDepositsController::class, 'updateStatus'])
        ->name('cash_deposits.update_status');
    Route::post('/cash-deposits/search', [CashDepositsController::class, 'search'])->name('cash_deposits.search');
    Route::post('/cash-deposits/filter', [CashDepositsController::class, 'filter'])->name('cash_deposits.filter');

    Route::get('/cheque-deposits', [ChequeDepositsController::class, 'index'])->name('cheque_deposits.index');
    Route::get('/cheque-deposits/download/{id}', [ChequeDepositsController::class, 'downloadAttachment'])->name('cheque_deposits.download');
    Route::get('/cheque-deposits/{id}', [ChequeDepositsController::class, 'show'])
        ->name('cheque_deposits.show');
    Route::post('/cheque-deposits/update-status/{id}', [ChequeDepositsController::class, 'updateStatus'])
        ->name('cheque_deposits.update_status');
    Route::post('/cheque-deposits/search', [ChequeDepositsController::class, 'search'])->name('cheque_deposits.search');
    Route::post('/cheque-deposits/filter', [ChequeDepositsController::class, 'filter'])->name('cheque_deposits.filter');

    Route::get('/finance-cash', [FinanceCashController::class, 'index'])->name('finance_cash.index');
    Route::get('/finance-cash/{id}', [FinanceCashController::class, 'show'])->name('finance_cash.show');
    Route::get('/finance-cash/download/{id}', [FinanceCashController::class, 'downloadAttachment'])->name('finance_cash.download');
    Route::post('/finance-cash/update-status/{id}', [FinanceCashController::class, 'updateStatus'])->name('finance_cash.update_status');
    Route::post('/finance-cash/search', [FinanceCashController::class, 'search'])->name('finance_cash.search');
    Route::post('/finance-cash/filter', [FinanceCashController::class, 'filter'])->name('finance_cash.filter');

    Route::get('/finance-cheque', [FinanceChequeController::class, 'index'])->name('finance_cheque.index');
    Route::get('/finance-cheque/download/{id}', [FinanceChequeController::class, 'downloadAttachment'])->name('finance_cheque.download');
    Route::get('/finance-cheque/{id}', [FinanceChequeController::class, 'show'])->name('finance_cheque.show');
    Route::post('/finance-cheque/update-status/{id}', [FinanceChequeController::class, 'updateStatus'])->name('finance_cheque.update_status');
    Route::post('/finance-cheque/search', [FinanceChequeController::class, 'search'])->name('finance_cheque.search');
    Route::post('/finance-cheque/filter', [FinanceChequeController::class, 'filter'])->name('finance_cheque.filter');

    Route::get('/fund-transfers', [\Modules\Finance\Http\Controllers\FundTransferController::class, 'index'])
        ->name('fund_transfers.index');
    Route::get('/fund-transfers/{id}', [FundTransferController::class, 'show'])
        ->name('fund_transfers.show');

    Route::get('/write-off', [WriteOffController::class, 'index'])->name('write_off.index');
    Route::post('/write-off/invoices', [WriteOffController::class, 'getInvoices'])->name('write_off.invoices');
    Route::post('/write-off/credit-notes', [WriteOffController::class, 'getCreditNotes'])->name('write_off.credit_notes');
    Route::post('/write-off/extra-payments', [WriteOffController::class, 'getExtraPayments'])->name('write_off.extra_payments');
    Route::post('/write-off/submit', [WriteOffController::class, 'submitWriteOff'])->name('write_off.submit');
    Route::get('/write-off-main', [WriteOffController::class, 'main'])->name('write_off.main');
    Route::get('/write-off-details/{id}', [WriteOffController::class, 'details'])->name('write_off.details');
    Route::get('/write-off/download/{id}', [WriteOffController::class, 'download'])->name('write_off.download');

    Route::get('/set-off', [SetOffController::class, 'index'])->name('set_off.index');
    Route::post('/set-off/invoices', [SetOffController::class, 'getInvoices'])->name('set_off.invoices');
    Route::post('/set-off/credit-notes', [SetOffController::class, 'getCreditNotes'])->name('set_off.credit_notes');
    Route::post('/set-off/submit', [SetOffController::class, 'submitSetOff'])->name('set_off.submit');
    Route::get('/set-off-main', [SetOffController::class, 'main'])->name('set_off.main');
    Route::get('/set-off-details/{id}', [SetOffController::class, 'details'])->name('set_off.details');
    Route::get('/set-off/download/{id}', [SetOffController::class, 'download'])->name('set_off.download');

    Route::get('/all-collections', [CollectionsController::class, 'all_collections'])
        ->name('collections.all');
    Route::get('/collection-details/{id}', [CollectionsController::class, 'collection_details'])
        ->name('collections.details');
    Route::post('/all-collections/search', [CollectionsController::class, 'search_collections'])
        ->name('collections.search');
    Route::post('/collections/filter', [CollectionsController::class, 'filter_collections'])->name('collections.filter');
});
