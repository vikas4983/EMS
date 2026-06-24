<?php

use App\Http\Controllers\Auth\Admin\CategoryController;
use App\Http\Controllers\Auth\Admin\FilterExpenseController;
use App\Http\Controllers\Auth\Employee\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashbaord'])->name('dashboard');
    Route::get('get-expenses', [ExpenseController::class, 'getExpense'])->name('get-expenses');
    Route::resource('expenses', ExpenseController::class);
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::post('expenses/{id}', [ExpenseController::class, 'restore'])->name('expenses.restore');
        Route::post('forceDelete/{id}', [ExpenseController::class, 'forceDelete'])->name('expenses.forceDelete');
        Route::get('trashed-expenses', [ExpenseController::class, 'trashed'])->name('expenses.trashed');
        Route::get('receipts/{receipt}', [ExpenseController::class, 'download'])->name('receipts.download');
        Route::get('expense-filter', [FilterExpenseController::class, 'filter'])->name('expense.filter');
    });
    Route::middleware(['role:manager'])->group(function () {
        Route::post('approve-expense', [ExpenseController::class, 'approve'])->name('expense.approve');
        Route::post('reject-expense', [ExpenseController::class, 'reject'])->name('expense.reject');
    });
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
