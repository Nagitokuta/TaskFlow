<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showSignupForm'])->name('register');
Route::post('/signup', [RegisterController::class, 'signup'])->name('signup');

Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('tasks.index'));
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])->name('tasks.comments.store');
    Route::put('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update');
});
