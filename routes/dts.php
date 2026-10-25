<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // --------------------------------------------------------------------
    // Document Processing Routes
    // --------------------------------------------------------------------
    Route::prefix('documents')->name('documents.')->group(function () {

        // View All Documents (Accessible to all authenticated staff)
        Route::get('/', [DocumentController::class, 'index'])->name('index');

        // Archived Documents Vault
        Route::get('/archived', [DocumentController::class, 'archived'])->name('archived');

        // Document Creation (Requires 'documents.create' permission)

        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');


        // Incoming Queue & Receiving Actions (Requires 'documents.receive' permission)

        Route::get('/incoming', [DocumentController::class, 'incoming'])->name('incoming');
        Route::post('/{document}/receive', [DocumentController::class, 'receive'])->name('receive');


        // Forwarding & Approving Documents (Requires 'documents.approve' permission)

        Route::post('/{document}/forward', [DocumentController::class, 'forward'])->name('forward');
        Route::post('/{document}/approve', [DocumentController::class, 'approve'])->name('approve');
        Route::post('/{document}/archive', [DocumentController::class, 'archive'])->name('archive');
        Route::get('/pending', [DocumentController::class, 'pending'])->name('pending');
        Route::post('/{document}/release', [DocumentController::class, 'release'])->name('release');
        Route::post('/{document}/archive', [DocumentController::class, 'archive'])->name('archive');

        Route::get('/forwarded', [DocumentController::class, 'forwarded'])->name('forwarded');

        Route::post('/{document}/complete', [DocumentController::class, 'complete'])->name('complete');
        Route::get('/completed', [DocumentController::class, 'completed'])->name('completed');

        Route::get('/transit', [DocumentController::class, 'transit'])->name('transit');

        Route::post('/transmittal', [DocumentController::class, 'generateTransmittal'])->name('transmittal');

        Route::get('/my-documents', [DocumentController::class, 'myDocuments'])->name('my-documents');

        Route::get('/rejected', [DocumentController::class, 'rejected'])->name('rejected');

        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        // Show Single Document Details & Routing History
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
    });

    // Print Batch Transmittal
    Route::get('/batches/{id}/print', [BatchController::class, 'printBatch'])->name('batches.print');

    // QR Code Scanning Target Routes
    Route::get('/batches/receive/{batch_number}', [BatchController::class, 'showReceiveView'])->name('batches.receive.view');
    Route::post('/batches/{id}/process-receive', [BatchController::class, 'processBatchReceive'])->name('batches.receive.process');

    Route::post('/batches/dispatch', [BatchController::class, 'dispatchBatch'])->name('batches.dispatch');
    // --------------------------------------------------------------------
    // Master References & System Administration (Admin / SuperAdmin Only)
    // --------------------------------------------------------------------


    // Departments Management
    Route::resource('departments', DepartmentController::class);

    // Offices Management
    Route::resource('offices', OfficeController::class);

    // Document Types Management
    Route::resource('document-types', DocumentTypeController::class);

    // Update user status route
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
    // User Accounts Management
    Route::resource('users', UserController::class);

    // Roles & Permissions Management
    Route::resource('roles', RoleController::class);
});
