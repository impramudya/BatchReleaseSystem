<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductMasterController;
use App\Http\Controllers\CategoryMasterController;
use App\Http\Controllers\ChecklistConfigController;
use App\Http\Controllers\ChecklistCategoryController;
use App\Http\Controllers\ChecklistQuestionController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/password/change', [PasswordChangeController::class, 'edit'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management.index');
    Route::get('/user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
    Route::post('/user-management', [UserManagementController::class, 'store'])->name('user-management.store');
    Route::get('/user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
    Route::put('/user-management/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
    Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    Route::post('/user-management/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');

// Category Master
    Route::get('/category-master', [CategoryMasterController::class, 'index'])
        ->name('category-master.index');
    Route::get('/category-master/create', [CategoryMasterController::class, 'create'])
        ->name('category-master.create');
    Route::post('/category-master', [CategoryMasterController::class, 'store'])
        ->name('category-master.store');
    Route::get('/category-master/{category}/edit', [CategoryMasterController::class, 'edit'])
        ->name('category-master.edit');
    Route::put('/category-master/{category}', [CategoryMasterController::class, 'update'])
        ->name('category-master.update');
    Route::delete('/category-master/{category}', [CategoryMasterController::class, 'destroy'])
        ->name('category-master.destroy');

//product master
    Route::get('/product-master', [ProductMasterController::class, 'index'])
        ->name('product-master.index');
    Route::get('/product-master/create', [ProductMasterController::class, 'create'])
        ->name('product-master.create');
    Route::post('/product-master', [ProductMasterController::class, 'store'])
        ->name('product-master.store');
    Route::get('/product-master/{product}/edit', [ProductMasterController::class, 'edit'])
        ->name('product-master.edit');
    Route::put('/product-master/{product}', [ProductMasterController::class, 'update'])
        ->name('product-master.update');
    Route::delete('/product-master/{product}', [ProductMasterController::class, 'destroy'])
        ->name('product-master.destroy');

//chekslist config
    Route::get('/checklist-config/{category?}', [ChecklistConfigController::class, 'index'])
        ->name('checklist-config.index');

    // CRUD Kategori Evaluasi
    Route::post('/checklist-categories', [ChecklistCategoryController::class, 'store'])
        ->name('checklist-categories.store');
    Route::put('/checklist-categories/{category}', [ChecklistCategoryController::class, 'update'])
        ->name('checklist-categories.update');
    Route::delete('/checklist-categories/{category}', [ChecklistCategoryController::class, 'destroy'])
        ->name('checklist-categories.destroy');

    // CRUD Pertanyaan
    Route::post('/checklist-categories/{category}/questions', [ChecklistQuestionController::class, 'store'])
        ->name('checklist-questions.store');
    Route::put('/checklist-questions/{question}', [ChecklistQuestionController::class, 'update'])
        ->name('checklist-questions.update');
    Route::delete('/checklist-questions/{question}', [ChecklistQuestionController::class, 'destroy'])
        ->name('checklist-questions.destroy');
    Route::patch('/checklist-questions/{question}/toggle-status', [ChecklistQuestionController::class, 'toggleStatus'])
        ->name('checklist-questions.toggle-status');

});


Route::get('/', function () {
    return redirect()->route('login');
});