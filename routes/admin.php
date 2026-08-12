<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LeadManagementController;
use App\Http\Controllers\Admin\StaffManagementController;

Route::prefix('admin')->name('admin.')->group(function () {

    // ---------------- Leads ----------------
    Route::get('/leads', [LeadManagementController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [LeadManagementController::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadManagementController::class, 'store'])->name('leads.store');
    Route::get('/leads/{lead}', [LeadManagementController::class, 'show'])->name('leads.show');
    Route::get('/leads/{lead}/edit', [LeadManagementController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [LeadManagementController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadManagementController::class, 'destroy'])->name('leads.destroy');
//lead assign

Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::get('/leads/{lead}/assign', [LeadManagementController::class, 'assignForm'])->name('admin.leads.assign.form');
    Route::post('/leads/{lead}/assign', [LeadManagementController::class, 'assign'])->name('admin.leads.assign.store');
    Route::post('/leads/{lead}/assign-self', [LeadManagementController::class, 'assignToSelf'])->name('admin.leads.assign-self');
});

    // ---------------- Categories (top-level, NOT nested under leads) ----------------
    Route::get('/categories', [LeadManagementController::class, 'catagoriesIndex'])->name('categories.index');
    Route::post('/categories', [LeadManagementController::class, 'storeCat'])->name('categories.store');
    Route::put('/categories/{category}', [LeadManagementController::class, 'updateCat'])->name('categories.update');
    Route::patch('/categories/{category}/toggle-status', [LeadManagementController::class, 'toggleStatusCat'])->name('categories.toggle-status');
    Route::delete('/categories/{category}', [LeadManagementController::class, 'destroyCat'])->name('categories.destroy');

    //Staff Management
    Route::get('/staffs', [StaffManagementController::class, 'index'])->name('staffs.index');
    Route::get('/staffs/create', [StaffManagementController::class, 'create'])->name('staffs.create');
    Route::post('/staffs', [StaffManagementController::class, 'store'])->name('staffs.store');
    Route::get('/staffs/{staffs}/edit', [StaffManagementController::class, 'edit'])->name('staffs.edit');
    Route::put('/staffs/{staffs}', [StaffManagementController::class, 'update'])->name('staffs.update');
    Route::patch('/staffs/{staffs}/toggle-status', [StaffManagementController::class, 'toggleStatus'])->name('users.toggleStatus');


});