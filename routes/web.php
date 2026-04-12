<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\TagSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated + verified routes
Route::middleware(['auth', 'verified.alumni'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::post('/approvals/{user}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{user}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

    // Job Board
    Route::get('/jobs', [JobPostController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [JobPostController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobPostController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{jobPost}', [JobPostController::class, 'show'])->name('jobs.show');

    // Tag Subscriptions
    Route::post('/tags/{tag}/toggle', [TagSubscriptionController::class, 'toggle'])->name('tags.toggle');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
