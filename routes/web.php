<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/callback', [App\Http\Controllers\AuthController::class, 'callback']);
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard', ['user' => Session::get('user'), 'token' => Session::get('access_token')]);
})->middleware(Authenticate::class.':cognito')->name('dashboard');
