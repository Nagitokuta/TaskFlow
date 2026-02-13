<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
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
    Route::get('/your-tasks', [TaskController::class, 'yourTasks'])->name('your_tasks');
    Route::get('/wait_approval_tasks', [TaskController::class, 'wait_approval_tasks'])->name('wait_approval_tasks');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
});
