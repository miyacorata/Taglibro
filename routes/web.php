<?php

declare(strict_types=1);

use App\Http\Controllers\ArticleDataController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\VisitorController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', [VisitorController::class, 'index'])->name('index');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/callback', [App\Http\Controllers\AuthController::class, 'callback']);
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/article/{slug}', [VisitorController::class, 'article'])->name('viewArticle');

Route::prefix('admin')->middleware(Authenticate::class.':cognito')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [UserDataController::class, 'index'])->name('dashboard');
    Route::post('/user/update', [UserDataController::class, 'update'])->name('user.update');
    Route::resource('/article', ArticleDataController::class)->names('article');
});
