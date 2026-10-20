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
use App\Http\Controllers\BatchFormController;
use App\Http\Controllers\BatchRepositoryController;
use App\Http\Controllers\TaskApprovalController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\NotificationController;

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
    Route::get('/checklist-config/{line?}/{category?}', [ChecklistConfigController::class, 'index'])
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

    //form baru
    Route::get('/batch-form/create/{line?}', [BatchFormController::class, 'create'])
        ->name('batch-form.create');
    Route::post('/batch-form', [BatchFormController::class, 'store'])
        ->name('batch-form.store');
    Route::get('/batch-form/{batch}/edit', [BatchFormController::class, 'edit'])
        ->name('batch-form.edit');
    Route::put('/batch-form/{batch}', [BatchFormController::class, 'update'])
        ->name('batch-form.update');

    // Batch Repository
    Route::get('/batch-repository', [BatchRepositoryController::class, 'index'])
        ->name('batch-repository.index');
    Route::get('/batch-repository/export/excel', [BatchRepositoryController::class, 'exportExcel'])
        ->name('batch-repository.export-excel');
    Route::get('/batch-repository/export/pdf', [BatchRepositoryController::class, 'exportPdf'])
        ->name('batch-repository.export-pdf');
    Route::get('/batch-repository/{batch}', [BatchRepositoryController::class, 'show'])
        ->name('batch-repository.show');
    Route::get('/batch-repository/{batch}/pdf', [BatchRepositoryController::class, 'pdf'])
        ->name('batch-repository.pdf');
    Route::get('/batch-repository/{batch}/pdf/download', [BatchRepositoryController::class, 'pdfDownload'])
        ->name('batch-repository.pdf-download');

    // Task Approval
    Route::get('/task-approval', [TaskApprovalController::class, 'index'])
        ->name('task-approval.index');
    Route::post('/task-approval/{batch}/approve', [TaskApprovalController::class, 'approve'])
        ->name('task-approval.approve');
    Route::post('/task-approval/{batch}/reject', [TaskApprovalController::class, 'reject'])
        ->name('task-approval.reject');

    // Audit Trail
    Route::get('/audit-trail', [AuditTrailController::class, 'index'])
        ->name('audit-trail.index');
    Route::get('/audit-trail/export/excel', [AuditTrailController::class, 'exportExcel'])
        ->name('audit-trail.export-excel');
    Route::get('/audit-trail/export/pdf', [AuditTrailController::class, 'exportPdf'])
        ->name('audit-trail.export-pdf');
    // Notifications (dipanggil AJAX dari bell icon)
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');
});

Route::get('/', function () {
    return redirect()->route('login');
});
