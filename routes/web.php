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

Route::get('/file-upload', [UploadController::class, 'index'])->name('fileupload.index');
Route::post('/file-upload', [UploadController::class, 'store'])->name('fileupload.store');

Route::match(['get', 'post'], '/activity-log', [ActivityController::class, 'activity_log'])->middleware(AuthAdmin::class);
Route::match(['get', 'post'], '/backup', [BackupController::class, 'backup'])->middleware(AuthAdmin::class);
