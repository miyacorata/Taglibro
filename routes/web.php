<?php

use App\Http\Controllers\UserDataController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/callback', [App\Http\Controllers\AuthController::class, 'callback']);
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(Authenticate::class.':cognito')->group(function () {
    Route::get('/dashboard', [UserDataController::class, 'index'])->name('dashboard');
    Route::post('/user/update', [UserDataController::class, 'update'])->name('user.update');
});
