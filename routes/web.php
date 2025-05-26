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

Route::get('/dashboard', [\App\Http\Controllers\UserDataController::class, 'index'])
    ->middleware(Authenticate::class.':cognito')->name('dashboard');
Route::post('/user/update', [\App\Http\Controllers\UserDataController::class, 'update'])->name('user.update');

Route::get('/test', function () {
    return dump(Auth::user()->getAuthIdentifier(), Session::get('user'));
});
