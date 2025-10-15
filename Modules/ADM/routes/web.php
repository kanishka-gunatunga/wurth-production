<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\ADM\Http\Controllers\UserController;
use Modules\ADM\Http\Controllers\CollectionsController;
use Modules\ADM\Http\Controllers\CustomerController;
use Modules\ADM\Http\Controllers\InquiryController;
use Modules\ADM\Http\Controllers\NotificationsRemindersController;
use Modules\ADM\Http\Controllers\AdvancedPaymentController;
use Modules\ADM\Http\Controllers\DepositeController;
use Modules\ADM\Http\Middleware\ADMAuthenticated;
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

Route::prefix('adm')->group(function() {
    Route::match(['get', 'post'],'/', [UserController::class, 'dashboard'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'logout', [UserController::class, 'logout'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'my-profile', [UserController::class, 'my_profile'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'edit-profile', [UserController::class, 'edit_profile'])->middleware(ADMAuthenticated::class);

    Route::match(['get', 'post'],'collections', [CollectionsController::class, 'collections'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'search-invoices', [CollectionsController::class, 'search_invoices'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'view-invoice/{id}', [CollectionsController::class, 'view_invoice'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-cash-payment/{id}', [CollectionsController::class, 'add_cash_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-fund-transfer/{id}', [CollectionsController::class, 'add_fund_transfer'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-cheque-payment/{id}', [CollectionsController::class, 'add_cheque_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-card-payment/{id}', [CollectionsController::class, 'add_card_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'save-invoice/{id}', [CollectionsController::class, 'save_invoice'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'resend-receipt/{id}', [CollectionsController::class, 'resend_receipt']);
    Route::match(['get', 'post'],'search-bulk-payment', [CollectionsController::class, 'search_bulk_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'bulk-payment', [CollectionsController::class, 'bulk_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'bulk-payment-submit', [CollectionsController::class, 'bulk_payment_submit'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-bulk-cash-payments', [CollectionsController::class, 'add_bulk_cash_payments'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-bulk-fund-transfer', [CollectionsController::class, 'add_bulk_fund_transfer'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-bulk-cheque-payment', [CollectionsController::class, 'add_bulk_cheque_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'add-bulk-card-payment', [CollectionsController::class, 'add_bulk_card_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'save-bulk-payment', [CollectionsController::class, 'save_bulk_payment'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'receipts', [CollectionsController::class, 'receipts'])->middleware(ADMAuthenticated::class);

    Route::match(['get', 'post'],'daily-deposit', [DepositeController::class, 'daily_deposit'])->middleware(ADMAuthenticated::class);

    Route::match(['get', 'post'],'customers', [CustomerController::class, 'customers'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'search-customers', [CustomerController::class, 'search_customers'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'search-temp-customers', [CustomerController::class, 'search_temp_customers'])->middleware(ADMAuthenticated::class);

    Route::match(['get', 'post'],'notifications-and-reminders', [NotificationsRemindersController::class, 'notifications_and_reminders'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'create-reminder', [NotificationsRemindersController::class, 'create_reminder'])->middleware(ADMAuthenticated::class);


    Route::match(['get', 'post'],'inquiries', [InquiryController::class, 'inquiries'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'create-inquiry', [InquiryController::class, 'create_inquiry'])->middleware(ADMAuthenticated::class);
    Route::get('/get-customer-invoices/{customerId}', [InquiryController::class, 'get_customer_invoices'])->middleware(ADMAuthenticated::class);
    Route::match(['get', 'post'],'search-inquiries', [InquiryController::class, 'search_inquiries'])->middleware(ADMAuthenticated::class);


    Route::match(['get', 'post'],'create-advanced-payment', [AdvancedPaymentController::class, 'create_advanced_payment'])->middleware(ADMAuthenticated::class);
    Route::get('/get-customer-details/{customerId}', [AdvancedPaymentController::class, 'get_customer_details'])->middleware(ADMAuthenticated::class);
});