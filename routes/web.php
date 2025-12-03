<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReturnChequeController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\InquiriesController;
use App\Http\Controllers\AdvancedPaymentsController;
use App\Http\Controllers\FinanceCashController;
use App\Http\Controllers\FinanceChequeController;
use App\Http\Controllers\CashDepositsController;
use App\Http\Controllers\ChequeDepositsController;

Route::match(['get', 'post'], '/', [UserController::class, 'index']);
Route::match(['get', 'post'], 'forgot-password', [UserController::class, 'forgot_password']);
Route::match(['get', 'post'], 'enter-otp', [UserController::class, 'enter_otp']);
Route::match(['get', 'post'], 'reset-password', [UserController::class, 'reset_password']);
Route::match(['get', 'post'], 'logout', [UserController::class, 'logout'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/dashboard', [UserController::class, 'dashboard'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/user-managment', [UserController::class, 'user_managment'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/add-new-user', [UserController::class, 'add_new_user'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/activate-user/{id}', [UserController::class, 'activate_user'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/deactivate-user/{id}', [UserController::class, 'deactivate_user'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/get-supervisors/{role}', [UserController::class, 'get_supervisors'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/edit-user/{id}', [UserController::class, 'edit_user'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/locked-users', [UserController::class, 'locked_users'])->middleware(AuthAdmin::class);
Route::get('unlock-user/{id}', [UserController::class, 'unlock_user']);
Route::match(['get', 'post'], '/settings', [UserController::class, 'settings'])->middleware(AuthAdmin::class);

Route::match(['get', 'post'], '/division-managment', [DivisionController::class, 'division_managment'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/add-new-division', [DivisionController::class, 'add_new_division'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/activate-division/{id}', [DivisionController::class, 'activate_division'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/deactivate-division/{id}', [DivisionController::class, 'deactivate_division'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/edit-division/{id}', [DivisionController::class, 'edit_division'])->middleware(AuthAdmin::class);

Route::match(['get', 'post'], '/access-control', [AccessController::class, 'access_control'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/get-role-permissions', [AccessController::class, 'get_role_permissions'])->middleware(AuthAdmin::class);

Route::match(['get', 'post'], '/customers', [CustomerController::class, 'customers'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/add-new-customer', [CustomerController::class, 'add_new_customer'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/activate-customer/{id}', [CustomerController::class, 'activate_customer'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/deactivate-customer/{id}', [CustomerController::class, 'deactivate_customer'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/edit-customer/{id}', [CustomerController::class, 'edit_customer'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/import-customers', [CustomerController::class, 'import_customers'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/import', [CustomerController::class, 'import'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/view-customer/{id}', [CustomerController::class, 'view_customer'])->middleware(AuthAdmin::class);

Route::match(['get', 'post'], 'get-branches', [CommonController::class, 'get_branches']);

Route::get('/create-reminder', [ReminderController::class, 'create'])->middleware(AuthAdmin::class);
Route::post('/create-reminder', [ReminderController::class, 'store'])->middleware(AuthAdmin::class)->name('reminders.store');
Route::get('/reminders', [ReminderController::class, 'index'])
    ->middleware(AuthAdmin::class)
    ->name('reminders.index');
Route::get('/reminders/{id}', [ReminderController::class, 'show'])
    ->middleware(AuthAdmin::class)
    ->name('reminders.show');
Route::get('/sent-reminders', [ReminderController::class, 'sentReminders'])
    ->middleware(AuthAdmin::class)
    ->name('reminders.sent');

Route::match(['get', 'post'], '/inquiries', [InquiriesController::class, 'inquiries'])->name('inquiries');
Route::get('/inquiry-details/{id}', [InquiriesController::class, 'details'])->name('inquiry.details');
Route::post('/inquiries/approve/{id}', [InquiriesController::class, 'approve'])->name('inquiries.approve');
Route::post('/inquiries/reject/{id}', [InquiriesController::class, 'reject'])->name('inquiries.reject');
Route::post('/inquiries/search', [InquiriesController::class, 'search'])->name('inquiries.search');
Route::post('/inquiries/filter', [InquiriesController::class, 'filter'])->name('inquiries.filter');

Route::get('/create-return-cheque', [ReturnChequeController::class, 'create'])->middleware(AuthAdmin::class);
Route::post('/create-return-cheque', [ReturnChequeController::class, 'store'])->middleware(AuthAdmin::class)->name('returncheques.store');
Route::get('/return-cheques', [ReturnChequeController::class, 'index'])
    ->middleware(AuthAdmin::class)
    ->name('returncheques.index');
Route::get('/return-cheques/{id}', [ReturnChequeController::class, 'show'])
    ->middleware(AuthAdmin::class)
    ->name('returncheques.show');
Route::post('/return-cheques/import', [ReturnChequeController::class, 'importReturnCheques'])->name('returncheques.import');


Route::match(['get', 'post'], '/all-outstanding', [CollectionsController::class, 'all_outstanding'])->middleware(AuthAdmin::class);

Route::match(['get', 'post'], '/all-receipts', [CollectionsController::class, 'all_receipts'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], 'resend-receipt/{id}', [CollectionsController::class, 'resend_receipt'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], 'remove-advanced-payment/{id}', [CollectionsController::class, 'remove_advanced_payment'])->middleware(AuthAdmin::class);

Route::get('/all-collections', [CollectionsController::class, 'all_collections'])
    ->middleware(AuthAdmin::class)
    ->name('collections.all');
Route::get('/collection-details/{id}', [CollectionsController::class, 'collection_details'])
    ->middleware(AuthAdmin::class)
    ->name('collections.details');
Route::post('/all-collections/search', [CollectionsController::class, 'search_collections'])
    ->middleware(AuthAdmin::class)
    ->name('collections.search');
Route::get('/collections/filter', [CollectionsController::class, 'filter_collections'])
    ->middleware(AuthAdmin::class)
    ->name('collections.filter');
Route::get('/collections/add', [CollectionsController::class, 'add_new_collection'])
    ->middleware(AuthAdmin::class)
    ->name('collections.add');
Route::get('/collections/customers/all', [CollectionsController::class, 'getAllCustomers'])
    ->middleware(AuthAdmin::class)
    ->name('collections.customers.all');
Route::get('/collections/customer/details/{id}', [CollectionsController::class, 'getCustomerDetails'])
    ->middleware(AuthAdmin::class);
Route::get('/collections/customer/invoices/{id}', [CollectionsController::class, 'getCustomerInvoices'])
    ->middleware(AuthAdmin::class);
Route::get('/collections/invoices', function () {
    return view('collections.invoices');
})
    ->middleware(AuthAdmin::class)
    ->name('collections.invoices');
Route::post('/collections/export', [CollectionsController::class, 'export_collections'])
    ->middleware(AuthAdmin::class)
    ->name('collections.export');

Route::get('/advanced-payments', [AdvancedPaymentsController::class, 'index'])->middleware(AuthAdmin::class)
    ->name('advanced_payments.index');
Route::get('/advance-payments-details/{id}', [AdvancedPaymentsController::class, 'show'])
    ->middleware(AuthAdmin::class)
    ->name('advanced_payments.show');
Route::post('/advanced-payments/search', [AdvancedPaymentsController::class, 'search'])
    ->middleware(AuthAdmin::class)
    ->name('advanced_payments.search');

Route::get('/finance-cash', [FinanceCashController::class, 'index'])->name('finance_cash.index')->middleware(AuthAdmin::class);
Route::get('/finance-cash/{id}', [FinanceCashController::class, 'show'])->name('finance_cash.show')->middleware(AuthAdmin::class);
Route::get('/finance-cash/download/{id}', [FinanceCashController::class, 'downloadAttachment'])->name('finance_cash.download')->middleware(AuthAdmin::class);
Route::post('/finance-cash/update-status/{id}', [FinanceCashController::class, 'updateStatus'])->name('finance_cash.update_status')->middleware(AuthAdmin::class);
Route::post('/finance-cash/search', [FinanceCashController::class, 'search'])->name('finance_cash.search')->middleware(AuthAdmin::class);
Route::post('/finance-cash/filter', [FinanceCashController::class, 'filter'])->name('finance_cash.filter')->middleware(AuthAdmin::class);
Route::post('/finance-cash/export', [FinanceCashController::class, 'exportFiltered'])->name('finance_cash.export')->middleware(AuthAdmin::class);

Route::get('/finance-cheque', [FinanceChequeController::class, 'index'])->name('finance_cheque.index')->middleware(AuthAdmin::class);
Route::get('/finance-cheque/download/{id}', [FinanceChequeController::class, 'downloadAttachment'])->name('finance_cheque.download')->middleware(AuthAdmin::class);
Route::get('/finance-cheque/{id}', [FinanceChequeController::class, 'show'])->name('finance_cheque.show')->middleware(AuthAdmin::class);
Route::post('/finance-cheque/update-status/{id}', [FinanceChequeController::class, 'updateStatus'])->name('finance_cheque.update_status')->middleware(AuthAdmin::class);
Route::post('/finance-cheque/search', [FinanceChequeController::class, 'search'])->name('finance_cheque.search')->middleware(AuthAdmin::class);
Route::post('/finance-cheque/filter', [FinanceChequeController::class, 'filter'])->name('finance_cheque.filter')->middleware(AuthAdmin::class);
Route::post('/finance-cheque/export', [FinanceChequeController::class, 'export'])->name('finance_cheque.export');

Route::get('/cash-deposits', [CashDepositsController::class, 'index'])->name('cash_deposits.index');
Route::get('/cash-deposits/{id}', [CashDepositsController::class, 'show'])->name('cash_deposits.show');
Route::get('/cash-deposits/download/{id}', [CashDepositsController::class, 'downloadAttachment'])
    ->name('cash_deposits.download');
Route::post('/cash-deposits/update-status/{id}', [CashDepositsController::class, 'updateStatus'])
    ->name('cash_deposits.update_status');
Route::post('/cash-deposits/search', [CashDepositsController::class, 'search'])->name('cash_deposits.search');
Route::post('/cash-deposits/filter', [CashDepositsController::class, 'filter'])->name('cash_deposits.filter');
Route::post('/cash-deposits/export', [CashDepositsController::class, 'export'])->name('cash_deposits.export');

Route::get('/cheque-deposits', [ChequeDepositsController::class, 'index'])->name('cheque_deposits.index');
Route::get('/cheque-deposits/download/{id}', [ChequeDepositsController::class, 'downloadAttachment'])->name('cheque_deposits.download');
Route::get('/cheque-deposits/{id}', [ChequeDepositsController::class, 'show'])
    ->name('cheque_deposits.show');
Route::post('/cheque-deposits/update-status/{id}', [ChequeDepositsController::class, 'updateStatus'])
    ->name('cheque_deposits.update_status');
Route::post('/cheque-deposits/search', [ChequeDepositsController::class, 'search'])->name('cheque_deposits.search');
Route::post('/cheque-deposits/filter', [ChequeDepositsController::class, 'filter'])->name('cheque_deposits.filter');
Route::post('/cheque-deposits/export', [ChequeDepositsController::class, 'export'])->name('cheque_deposits.export');

Route::get('/file-upload', [UploadController::class, 'index'])->name('fileupload.index');
Route::post('/file-upload', [UploadController::class, 'store'])->name('fileupload.store');

Route::match(['get', 'post'], '/activity-log', [ActivityController::class, 'activity_log'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/backup', [BackupController::class, 'backup'])->middleware(AuthAdmin::class);
