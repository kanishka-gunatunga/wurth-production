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
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\CardPaymentController;
use App\Http\Controllers\WriteOffController;
use App\Http\Controllers\SetOffController;
use App\Http\Controllers\ReportsController;

Route::match(['get', 'post'], '/', [UserController::class, 'index']);
Route::match(['get', 'post'], 'forgot-password', [UserController::class, 'forgot_password']);
Route::match(['get', 'post'], 'enter-otp', [UserController::class, 'enter_otp']);
Route::match(['get', 'post'], 'reset-password', [UserController::class, 'reset_password']);
Route::match(['get', 'post'], 'logout', [UserController::class, 'logout'])->middleware(['authAdmin']);
Route::match(['get', 'post'], '/dashboard', [UserController::class, 'dashboard'])->middleware(['authAdmin', 'permission:dashboard']);
Route::match(['get', 'post'], '/user-managment', [UserController::class, 'user_managment'])->middleware(['authAdmin', 'permission:user-Management']);
Route::match(['get', 'post'], '/add-new-user', [UserController::class, 'add_new_user'])->middleware(['authAdmin', 'permission:add-user']);
Route::match(['get', 'post'], '/activate-user/{id}', [UserController::class, 'activate_user'])->middleware(['authAdmin', 'permission:status-change-user']);
Route::match(['get', 'post'], '/deactivate-user/{id}', [UserController::class, 'deactivate_user'])->middleware(['authAdmin', 'permission:status-change-user']);
Route::match(['get', 'post'], '/get-supervisors/{role}', [UserController::class, 'get_supervisors'])->middleware(['authAdmin']);
Route::match(['get', 'post'], '/edit-user/{id}', [UserController::class, 'edit_user'])->middleware(['authAdmin', 'permission:edit-user']);
Route::match(['get', 'post'], '/locked-users', [UserController::class, 'locked_users'])->middleware(['authAdmin', 'permission:security-locked']);
Route::get('unlock-user/{id}', [UserController::class, 'unlock_user'])->middleware(['authAdmin', 'permission:security-locked-unlock']);
Route::match(['get', 'post'], '/settings', [UserController::class, 'settings'])->middleware(['authAdmin', 'permission:settings']);
Route::post('/update-profile-picture', [UserController::class, 'updateProfilePicture'])->middleware(['authAdmin']);
Route::post('/delete-profile-picture', [UserController::class, 'deleteProfilePicture'])->middleware(['authAdmin']);
Route::post('/get-user-details-divison-role', [UserController::class, 'getUserDetailsDivisonRole'])->middleware(['authAdmin']);
Route::post('/get-user-details-divison-role-with-roles', [UserController::class, 'getUserDetailsDivisonRoleWithRoles'])->middleware(['authAdmin']);
Route::post('/switch-user', [UserController::class, 'switch_user'])->middleware(['authAdmin']);
Route::post('/replace-user', [UserController::class, 'replace_user'])->middleware(['authAdmin']);
Route::post('/promote-user', [UserController::class, 'promote_user'])->middleware(['authAdmin']);

Route::match(['get', 'post'], '/division-managment', [DivisionController::class, 'division_managment'])->middleware(['authAdmin', 'permission:division-management']);
Route::match(['get', 'post'], '/add-new-division', [DivisionController::class, 'add_new_division'])->middleware(['authAdmin', 'permission:add-division']);
Route::match(['get', 'post'], '/activate-division/{id}', [DivisionController::class, 'activate_division'])->middleware(['authAdmin', 'permission:status-change-division']);
Route::match(['get', 'post'], '/deactivate-division/{id}', [DivisionController::class, 'deactivate_division'])->middleware(['authAdmin', 'permission:status-change-division']);
Route::match(['get', 'post'], '/edit-division/{id}', [DivisionController::class, 'edit_division'])->middleware(['authAdmin', 'permission:edit-division']);
Route::match(['get', 'post'], '/delete-division/{id}', [DivisionController::class, 'delete_division'])->middleware(['authAdmin', 'permission:delete-division']);

Route::match(['get', 'post'], '/access-control', [AccessController::class, 'access_control'])->middleware(['authAdmin', 'permission:access-control']);
Route::match(['get', 'post'], '/get-role-permissions', [AccessController::class, 'get_role_permissions'])->middleware(['authAdmin']);

Route::match(['get', 'post'], '/customers', [CustomerController::class, 'customers'])->middleware(['authAdmin', 'permission:access-control']);
Route::match(['get', 'post'], '/add-new-customer', [CustomerController::class, 'add_new_customer'])->middleware(['authAdmin', 'permission:add-customer']);
Route::match(['get', 'post'], '/activate-customer/{id}', [CustomerController::class, 'activate_customer'])->middleware(['authAdmin']);
Route::match(['get', 'post'], '/deactivate-customer/{id}', [CustomerController::class, 'deactivate_customer'])->middleware(['authAdmin']);
Route::match(['get', 'post'], '/edit-customer/{id}', [CustomerController::class, 'edit_customer'])->middleware(['authAdmin', 'permission:all-customers-edit']);
Route::match(['get', 'post'], '/import-customers', [CustomerController::class, 'import_customers'])->middleware(['authAdmin', 'permission:add-customer']);
Route::match(['get', 'post'], '/import', [CustomerController::class, 'import'])->middleware(['authAdmin', 'permission:add-customer']);
Route::match(['get', 'post'], '/view-customer/{id}', [CustomerController::class, 'view_customer'])->middleware(['authAdmin', 'permission:all-customers-view']);

Route::match(['get', 'post'], 'get-branches', [CommonController::class, 'get_branches']);

Route::get('/create-reminder', [ReminderController::class, 'create'])->middleware(['authAdmin', 'permission:notification-create']);
Route::post('/create-reminder', [ReminderController::class, 'store'])->middleware(['authAdmin', 'permission:notification-create'])->name('reminders.store');
Route::get('/get-users-by-level/{level}', [ReminderController::class, 'getUsersByLevel'])
    ->middleware(['authAdmin'])
    ->name('users.byLevel');
Route::get('/reminders', [ReminderController::class, 'index'])
    ->middleware(['authAdmin', 'permission:notifications'])
    ->name('reminders.index');
Route::get('/reminders/{id}', [ReminderController::class, 'show'])
    ->middleware(['authAdmin'])
    ->name('reminders.show');
Route::get('/sent-reminders', [ReminderController::class, 'sentReminders'])
    ->middleware(['authAdmin'])
    ->name('reminders.sent');
Route::match(['get', 'post'], '/view-deposit-reminder/{id}', [ReminderController::class, 'view_deposit_reminder'])->middleware(['authAdmin']);

Route::match(['get', 'post'], '/inquiries', [InquiriesController::class, 'inquiries'])->middleware(['authAdmin', 'permission:inquaries'])->name('inquiries');
Route::get('/inquiry-details/{id}', [InquiriesController::class, 'details'])->middleware(['authAdmin'])->name('inquiry.details');
Route::post('/inquiries/approve/{id}', [InquiriesController::class, 'approve'])->middleware(['authAdmin', 'permission:status-change-inquary'])->name('inquiries.approve');
Route::post('/inquiries/reject/{id}', [InquiriesController::class, 'reject'])->middleware(['authAdmin', 'permission:status-change-inquary'])->name('inquiries.reject');
Route::get('/inquiries/download/{id}', [InquiriesController::class, 'downloadAttachment'])
    ->name('inquiries.download')->middleware(['authAdmin']);
Route::post('/inquiries/search', [InquiriesController::class, 'search'])->middleware(['authAdmin', 'permission:inquaries'])->name('inquiries.search');
Route::post('/inquiries/filter', [InquiriesController::class, 'filter'])->middleware(['authAdmin', 'permission:inquaries'])->name('inquiries.filter');

Route::get('/create-return-cheque', [ReturnChequeController::class, 'create'])->middleware(['authAdmin', 'permission:return-cheques-add']);
Route::post('/create-return-cheque', [ReturnChequeController::class, 'store'])->middleware(['authAdmin', 'permission:return-cheques-add'])->name('returncheques.store');
Route::get('/return-cheques', [ReturnChequeController::class, 'index'])
    ->middleware(['authAdmin', 'permission:return-cheques'])
    ->name('returncheques.index');
Route::get('/return-cheques/{id}', [ReturnChequeController::class, 'show'])
    ->middleware(['authAdmin', 'permission:return-cheques-view'])
    ->name('returncheques.show');
Route::post('/return-cheques/import', [ReturnChequeController::class, 'importReturnCheques'])
    ->middleware(['authAdmin', 'permission:return-cheques-add'])
    ->name('returncheques.import');

Route::match(['get', 'post'], '/all-outstanding', [CollectionsController::class, 'all_outstanding'])->middleware(['authAdmin', 'permission:all-outstanding']);

Route::match(['get', 'post'], '/all-receipts', [CollectionsController::class, 'all_receipts'])->middleware(['authAdmin', 'permission:all-receipts']);
Route::match(['get', 'post'], 'resend-receipt/{id}', [CollectionsController::class, 'resend_receipt'])->middleware(['authAdmin', 'permission:all-receipts-final-sms']);
Route::match(['get', 'post'], 'remove-advanced-payment/{id}', [CollectionsController::class, 'remove_advanced_payment'])->middleware(['authAdmin']);

Route::get('/all-collections', [CollectionsController::class, 'all_collections'])
    ->middleware(['authAdmin', 'permission:all-collections'])
    ->name('collections.all');
Route::get('/collection-details/{id}', [CollectionsController::class, 'collection_details'])
    ->middleware(['authAdmin', 'permission:all-collections-view'])
    ->name('collections.details');
Route::post('/all-collections/search', [CollectionsController::class, 'search_collections'])
    ->middleware(['authAdmin'])
    ->name('collections.search');
Route::get('/collections/filter', [CollectionsController::class, 'filter_collections'])
    ->middleware(['authAdmin'])
    ->name('collections.filter');
Route::get('/collections/add', [CollectionsController::class, 'add_new_collection'])
    ->middleware(['authAdmin', 'permission:all-collections-add'])
    ->name('collections.add');
Route::get('/collections/customers/all', [CollectionsController::class, 'getAllCustomers'])
    ->middleware(['authAdmin'])
    ->name('collections.customers.all');
Route::get('/collections/customer/details/{id}', [CollectionsController::class, 'getCustomerDetails'])
    ->middleware(['authAdmin']);
Route::get('/collections/customer/invoices/{id}', [CollectionsController::class, 'getCustomerInvoices'])
    ->middleware(['authAdmin']);
Route::get('/collections/invoices', function () {
    return view('collections.invoices');
})
    ->middleware(['authAdmin'])
    ->name('collections.invoices');
Route::post('/collections/export', [CollectionsController::class, 'export_collections'])
    ->middleware(['authAdmin'])
    ->name('collections.export');

Route::get('/advanced-payments', [AdvancedPaymentsController::class, 'index'])->middleware(['authAdmin', 'permission:all-advanced-payments'])
    ->name('advanced_payments.index');
Route::get('/advance-payments-details/{id}', [AdvancedPaymentsController::class, 'show'])
    ->middleware(['authAdmin'])
    ->name('advanced_payments.show');
Route::post('/advanced-payments/search', [AdvancedPaymentsController::class, 'search'])
    ->middleware(['authAdmin'])
    ->name('advanced_payments.search');
Route::post('/advanced-payments/update-status', [AdvancedPaymentsController::class, 'updateStatus'])
    ->middleware(['authAdmin'])
    ->name('advanced_payments.update_status');
Route::get('/advanced-payments/download/{id}', [AdvancedPaymentsController::class, 'downloadAttachment'])
    ->middleware(['authAdmin'])
    ->name('advanced_payments.download');

Route::get('/finance-cash', [FinanceCashController::class, 'index'])->name('finance_cash.index')->middleware(['authAdmin', 'permission:deposits-finance-cash']);
Route::get('/finance-cash/{id}', [FinanceCashController::class, 'show'])->name('finance_cash.show')->middleware(['authAdmin']);
Route::get('/finance-cash/download/{id}', [FinanceCashController::class, 'downloadAttachment'])->name('finance_cash.download')->middleware(['authAdmin']);
Route::post('/finance-cash/update-status/{id}', [FinanceCashController::class, 'updateStatus'])->name('finance_cash.update_status')->middleware(['authAdmin', 'permission:deposits-finance-cash-status']);
Route::post('/finance-cash/search', [FinanceCashController::class, 'search'])->name('finance_cash.search')->middleware(['authAdmin']);
Route::post('/finance-cash/filter', [FinanceCashController::class, 'filter'])->name('finance_cash.filter')->middleware(['authAdmin']);
Route::post('/finance-cash/export', [FinanceCashController::class, 'exportFiltered'])->name('finance_cash.export')->middleware(['authAdmin']);

Route::get('/finance-cheque', [FinanceChequeController::class, 'index'])->name('finance_cheque.index')->middleware(['authAdmin', 'permission:deposits-finance-cheque']);
Route::get('/finance-cheque/download/{id}', [FinanceChequeController::class, 'downloadAttachment'])->name('finance_cheque.download')->middleware(['authAdmin']);
Route::get('/finance-cheque/{id}', [FinanceChequeController::class, 'show'])->name('finance_cheque.show')->middleware(['authAdmin']);
Route::post('/finance-cheque/update-status/{id}', [FinanceChequeController::class, 'updateStatus'])->name('finance_cheque.update_status')->middleware(['authAdmin', 'permission:deposits-finance-cheque-status']);
Route::post('/finance-cheque/search', [FinanceChequeController::class, 'search'])->name('finance_cheque.search')->middleware(['authAdmin']);
Route::post('/finance-cheque/filter', [FinanceChequeController::class, 'filter'])->name('finance_cheque.filter')->middleware(['authAdmin']);
Route::post('/finance-cheque/export', [FinanceChequeController::class, 'export'])->name('finance_cheque.export')->middleware(['authAdmin']);

Route::get('/cash-deposits', [CashDepositsController::class, 'index'])->name('cash_deposits.index')->middleware(['authAdmin', 'permission:deposits-cash']);
Route::get('/cash-deposits/{id}', [CashDepositsController::class, 'show'])->name('cash_deposits.show')->middleware(['authAdmin', 'permission:deposits-cash-view']);
Route::get('/cash-deposits/download/{id}', [CashDepositsController::class, 'downloadAttachment'])
    ->name('cash_deposits.download')->middleware(['authAdmin', 'permission:deposits-cash-download']);
Route::post('/cash-deposits/update-status/{id}', [CashDepositsController::class, 'updateStatus'])
    ->name('cash_deposits.update_status')->middleware(['authAdmin', 'permission:deposits-cash-download']);
Route::post('/cash-deposits/search', [CashDepositsController::class, 'search'])->name('cash_deposits.search')->middleware(['authAdmin']);
Route::post('/cash-deposits/filter', [CashDepositsController::class, 'filter'])->name('cash_deposits.filter')->middleware(['authAdmin']);
Route::post('/cash-deposits/export', [CashDepositsController::class, 'export'])->name('cash_deposits.export')->middleware(['authAdmin']);

Route::get('/cheque-deposits', [ChequeDepositsController::class, 'index'])->name('cheque_deposits.index')->middleware(['authAdmin', 'permission:deposits-cheque']);
Route::get('/cheque-deposits/download/{id}', [ChequeDepositsController::class, 'downloadAttachment'])->name('cheque_deposits.download')->middleware(['authAdmin', 'permission:deposits-cheque-download']);
Route::get('/cheque-deposits/{id}', [ChequeDepositsController::class, 'show'])
    ->name('cheque_deposits.show')->middleware(['authAdmin', 'permission:deposits-cheque-view']);
Route::post('/cheque-deposits/update-status/{id}', [ChequeDepositsController::class, 'updateStatus'])
    ->name('cheque_deposits.update_status')->middleware(['authAdmin', 'permission:deposits-cheque-status']);
Route::post('/cheque-deposits/search', [ChequeDepositsController::class, 'search'])->name('cheque_deposits.search')->middleware(['authAdmin']);
Route::post('/cheque-deposits/filter', [ChequeDepositsController::class, 'filter'])->name('cheque_deposits.filter')->middleware(['authAdmin']);
Route::post('/cheque-deposits/export', [ChequeDepositsController::class, 'export'])->name('cheque_deposits.export')->middleware(['authAdmin']);

Route::get('/fund-transfers', [FundTransferController::class, 'index'])
    ->name('fund_transfers.index')->middleware(['authAdmin', 'permission:deposits-fund-transfer']);
Route::get('/fund-transfers/{id}', [FundTransferController::class, 'show'])
    ->name('fund_transfers.show')->middleware(['authAdmin', 'permission:deposits-fund-transfer-view']);
Route::post('/fund-transfers/update-status/{id}', [FundTransferController::class, 'updateStatus'])
    ->name('fund_transfers.update_status')->middleware(['authAdmin', 'permission:deposits-fund-transfer-status']);
Route::post('/fund-transfers/export', [FundTransferController::class, 'export'])
    ->name('fund_transfers.export')->middleware(['authAdmin']);

Route::get('/card-payments', [CardPaymentController::class, 'index'])->name('card_payments.index')->middleware(['authAdmin', 'permission:deposits-card-payment']);
Route::get('/card-payments/{id}', [CardPaymentController::class, 'show'])->name('card_payments.show')->middleware(['authAdmin', 'permission:deposits-card-payment-view']);
Route::post('/card-payments/update-status/{id}', [CardPaymentController::class, 'updateStatus'])
    ->name('card_payments.update_status')->middleware(['authAdmin', 'permission:deposits-card-payment-status']);
Route::post('/card-payments/export', [CardPaymentController::class, 'export'])->name('card_payments.export')->middleware(['authAdmin']);

Route::get('/write-off', [WriteOffController::class, 'index'])->name('write_off.index')->middleware(['authAdmin', 'permission:writeoff-writeback']);
Route::post('/write-off/invoices', [WriteOffController::class, 'getInvoices'])->name('write_off.invoices')->middleware(['authAdmin']);
Route::post('/write-off/credit-notes', [WriteOffController::class, 'getCreditNotes'])->name('write_off.credit_notes')->middleware(['authAdmin']);
Route::post('/write-off/extra-payments', [WriteOffController::class, 'getExtraPayments'])->name('write_off.extra_payments')->middleware(['authAdmin']);
Route::post('/write-off/submit', [WriteOffController::class, 'submitWriteOff'])->name('write_off.submit')->middleware(['authAdmin']);
Route::get('/write-off-main', [WriteOffController::class, 'main'])->name('write_off.main')->middleware(['authAdmin', 'permission:writeoff-writeback-add']);
Route::get('/write-off-details/{id}', [WriteOffController::class, 'details'])->name('write_off.details')->middleware(['authAdmin', 'permission:writeoff-writeback-view']);
Route::get('/write-off/download/{id}', [WriteOffController::class, 'download'])->name('write_off.download')->middleware(['authAdmin', 'permission:writeoff-writeback-download']);

Route::get('/set-off', [SetOffController::class, 'index'])->name('set_off.index')->middleware(['authAdmin', 'permission:setoff']);
Route::post('/set-off/invoices', [SetOffController::class, 'getInvoices'])->name('set_off.invoices')->middleware(['authAdmin']);
Route::post('/set-off/credit-notes', [SetOffController::class, 'getCreditNotes'])->name('set_off.credit_notes')->middleware(['authAdmin']);
Route::post('/set-off/extra-payments', [SetOffController::class, 'getExtraPayments'])
    ->name('set_off.extra_payments')->middleware(['authAdmin']);
Route::post('/set-off/submit', [SetOffController::class, 'submitSetOff'])->name('set_off.submit')->middleware(['authAdmin']);
Route::get('/set-off-main', [SetOffController::class, 'main'])->name('set_off.main')->middleware(['authAdmin']);
Route::get('/set-off-details/{id}', [SetOffController::class, 'details'])->name('set_off.details')->middleware(['authAdmin']);
Route::get('/set-off/download/{id}', [SetOffController::class, 'download'])->name('set_off.download')->middleware(['authAdmin', 'permission:setoff-download']);

Route::get('/file-upload', [UploadController::class, 'index'])->name('fileupload.index')->middleware(['authAdmin', 'permission:upload']);
Route::post('/file-upload', [UploadController::class, 'store'])->middleware(['authAdmin'])->name('admin.fileupload.store');

Route::match(['get', 'post'], '/activity-log', [ActivityController::class, 'activity_log'])->middleware(['authAdmin'])->middleware(['authAdmin', 'permission:security-activity']);
Route::match(['get', 'post'], '/backup', [BackupController::class, 'backup'])->middleware(['authAdmin'])->middleware(['authAdmin', 'permission:security-backup']);

Route::get('/reports', [ReportsController::class, 'index'])
    ->middleware(['authAdmin', 'permission:reports'])
    ->name('reports.index');


// extra dashboard routes
Route::get('/team-leader-dashboard', function () {
    return view('dashboard.team_leader_dashboard');
})->middleware(['authAdmin']);

Route::get('/area-sales-dashboard', function () {
    return view('dashboard.area_sales_dashboard');
})->middleware(['authAdmin']);