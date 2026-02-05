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

Route::prefix('adm')->group(function () { 
    Route::match(['get', 'post'], '/', [UserController::class, 'dashboard'])->middleware(['authADM', 'permission:dashboard']);
    Route::match(['get', 'post'], 'logout', [UserController::class, 'logout'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'my-profile', [UserController::class, 'my_profile'])->middleware(['authADM', 'permission:profile']);
    Route::match(['get', 'post'], 'edit-profile', [UserController::class, 'edit_profile'])->middleware(['authADM']);

    Route::match(['get', 'post'], 'collections', [CollectionsController::class, 'collections'])->middleware(['authADM', 'permission:collections']);
    Route::match(['get', 'post'], 'search-invoices', [CollectionsController::class, 'search_invoices'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'view-invoice/{id}', [CollectionsController::class, 'view_invoice'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-cash-payment/{id}', [CollectionsController::class, 'add_cash_payment'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-fund-transfer/{id}', [CollectionsController::class, 'add_fund_transfer'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-cheque-payment/{id}', [CollectionsController::class, 'add_cheque_payment'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-card-payment/{id}', [CollectionsController::class, 'add_card_payment'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'save-invoice/{id}', [CollectionsController::class, 'save_invoice'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'resend-receipt/{id}', [CollectionsController::class, 'resend_receipt']);
    Route::match(['get', 'post'], 'search-bulk-payment', [CollectionsController::class, 'search_bulk_payment'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'bulk-payment', [CollectionsController::class, 'bulk_payment'])->middleware(['authADM', 'permission:bulk-collection']);
    Route::match(['get', 'post'], 'bulk-payment-submit', [CollectionsController::class, 'bulk_payment_submit'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-bulk-cash-payments', [CollectionsController::class, 'add_bulk_cash_payments'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-bulk-fund-transfer', [CollectionsController::class, 'add_bulk_fund_transfer'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-bulk-cheque-payment', [CollectionsController::class, 'add_bulk_cheque_payment'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'add-bulk-card-payment', [CollectionsController::class, 'add_bulk_card_payment'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'save-bulk-payment', [CollectionsController::class, 'save_bulk_payment'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'receipts', [CollectionsController::class, 'receipts'])->middleware(['authADM', 'permission:all-reciepts']);
    Route::match(['get', 'post'], 'temporary-receipts', [CollectionsController::class, 'temporary_receipts'])->middleware(['authADM']);
    Route::match(['get', 'post'],'get-branches', [CollectionsController::class, 'get_branches'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'view-temp-receipt/{ID}', [CollectionsController::class, 'view_temp_receipt'])->middleware(['authADM']);


    Route::match(['get', 'post'], 'daily-deposit', [DepositeController::class, 'daily_deposit'])->middleware(['authADM', 'permission:deposit']);
    Route::match(['get', 'post'], 'get-receipts', [DepositeController::class, 'get_receipts'])->middleware(['authADM']);

    Route::match(['get', 'post'], 'customers', [CustomerController::class, 'customers'])->middleware(['authADM', 'permission:customers']);
    Route::match(['get', 'post'], 'search-customers', [CustomerController::class, 'search_customers'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'search-temp-customers', [CustomerController::class, 'search_temp_customers'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'update-customer-ajax', [CustomerController::class, 'update_customer_ajax'])->middleware(['authADM']);


    Route::match(['get', 'post'], 'notifications-and-reminders', [NotificationsRemindersController::class, 'notifications_and_reminders'])->middleware(['authADM', 'permission:reminders']);
    Route::match(['get', 'post'], 'create-reminder', [NotificationsRemindersController::class, 'create_reminder'])->middleware(['authADM']);
    Route::get('/get-users-by-level/{level}', [NotificationsRemindersController::class, 'getUsersByLevel'])
        ->middleware(['authADM']);
    Route::get('reminder-details/{id}', [NotificationsRemindersController::class, 'reminder_details'])
        ->middleware(['authADM']);


    Route::match(['get', 'post'], 'inquiries', [InquiryController::class, 'inquiries'])->middleware(['authADM', 'permission:inquiries']);
    Route::match(['get', 'post'], 'create-inquiry', [InquiryController::class, 'create_inquiry'])->middleware(['authADM']);
    Route::get('inquiry-details/{id}', [InquiryController::class, 'inquiry_details'])
        ->middleware(['authADM'])
        ->name('adm.inquiry.details');
    Route::get('/get-customer-invoices/{customerId}', [InquiryController::class, 'get_customer_invoices'])->middleware(['authADM']);
    Route::match(['get', 'post'], 'search-inquiries', [InquiryController::class, 'search_inquiries'])->middleware(['authADM']);
    Route::get(
        'inquiries/download/{id}',
        [InquiryController::class, 'downloadAttachment']
    )
        ->middleware(['authADM'])
        ->name('inquiries.download');


    Route::match(['get', 'post'], 'create-advanced-payment', [AdvancedPaymentController::class, 'create_advanced_payment'])->middleware(['authADM', 'permission:advanced-payment']);
    Route::get('/get-customer-details/{customerId}', [AdvancedPaymentController::class, 'get_customer_details'])->middleware(['authADM']);
    Route::get('advance-payments', [AdvancedPaymentController::class, 'advance_payments_list'])
        ->middleware(['authADM']);

    Route::get(
        'advance-payment/download/{id}',
        [AdvancedPaymentController::class, 'download_attachment']
    )
        ->name('advance_payment.download')
        ->middleware(['authADM']);
    Route::get(
        'advance-payment/details/{id}',
        [AdvancedPaymentController::class, 'advanced_payment_details']
    )->middleware(ADMAuthenticated::class)->name('advance_payment.details');




    // extra dashboard routes
    Route::get('/recovery-manager-dashboard', function () {
        return view('adm::dashboard.recovery_manager_dashboard');
    })->middleware(['authADM']);
});
