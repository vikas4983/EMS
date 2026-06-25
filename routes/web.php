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
    Route::get('receipts/{receipt}', [ExpenseController::class, 'download'])->name('receipts.download');
    Route::resource('expenses', ExpenseController::class);
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        // Expense
        Route::post('expenses/{id}', [ExpenseController::class, 'restore'])->name('expenses.restore');
        Route::post('forceDelete/{id}', [ExpenseController::class, 'forceDelete'])->name('expenses.forceDelete');
        Route::get('trashed-expenses', [ExpenseController::class, 'trashed'])->name('expenses.trashed');
        // category
        Route::post('category/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::post('category-force-delete/{id}', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
        Route::get('trashed-category', [CategoryController::class, 'trashed'])->name('categories.trashed');

        Route::get('expense-filter', [FilterExpenseController::class, 'filter'])->name('expense.filter');
    });
    Route::middleware(['role:manager'])->group(function () {
        Route::post('approve-expense', [ExpenseController::class, 'approve'])->name('expense.approve');
        Route::post('reject-expense', [ExpenseController::class, 'reject'])->name('expense.reject');
    });
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/latest', [NotificationController::class, 'latest'])->name('latest');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });
});
