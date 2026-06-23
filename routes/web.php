<?php

use App\Http\Controllers\Auth\Admin\CategoryController;
use App\Http\Controllers\Auth\Employee\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::post('expenses/{id}', [ExpenseController::class, 'restore'])->name('expenses.restore');
    Route::post('forceDelete/{id}', [ExpenseController::class, 'forceDelete'])->name('expenses.forceDelete');
    Route::get('trashed-expenses', [ExpenseController::class, 'trashed'])->name('expenses.trashed');
    Route::get('receipts/{receipt}', [ExpenseController::class, 'download'])->name('receipts.download');
    Route::post('approve-expense', [ExpenseController::class, 'approve'])->name('expense.approve');
    Route::post('reject-expense', [ExpenseController::class, 'reject'])->name('expense.reject');
});
