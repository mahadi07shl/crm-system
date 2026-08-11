<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\Authcontroller;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});



Route::get('/register', [Authcontroller::class, 'RegisterForm'])->name('register.form');
Route::post('/register', [Authcontroller::class, 'Register'])->name('register');

Route::get('/login', [Authcontroller::class, 'LoginForm'])->name('login.form');
Route::post('/login', [Authcontroller::class, 'Login'])->name('login');
Route::post('/logout',[Authcontroller::class,'Logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

