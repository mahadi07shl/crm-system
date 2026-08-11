<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LeadManagement;

Route::prefix('admin')->name('admin.')->group(function () {

    // ---------------- Leads ----------------
    Route::get('/leads', [LeadManagement::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [LeadManagement::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadManagement::class, 'store'])->name('leads.store');
    Route::get('/leads/{lead}', [LeadManagement::class, 'show'])->name('leads.show');
    Route::get('/leads/{lead}/edit', [LeadManagement::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [LeadManagement::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadManagement::class, 'destroy'])->name('leads.destroy');

    // ---------------- Categories (top-level, NOT nested under leads) ----------------
    Route::get('/categories', [LeadManagement::class, 'catagoriesIndex'])->name('categories.index');
    Route::post('/categories', [LeadManagement::class, 'storeCat'])->name('categories.store');
    Route::put('/categories/{category}', [LeadManagement::class, 'updateCat'])->name('categories.update');
    Route::patch('/categories/{category}/toggle-status', [LeadManagement::class, 'toggleStatusCat'])->name('categories.toggle-status');
    Route::delete('/categories/{category}', [LeadManagement::class, 'destroyCat'])->name('categories.destroy');

});