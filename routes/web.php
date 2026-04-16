<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;
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

    // Community Directory
    Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');

    // Profiles
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    // Admin Notices
    Route::middleware('admin')->group(function () {
        Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
        Route::get('/notices/create', [NoticeController::class, 'create'])->name('notices.create');
        Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
        Route::get('/notices/{notice}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
        Route::put('/notices/{notice}', [NoticeController::class, 'update'])->name('notices.update');
        Route::delete('/notices/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
        Route::get('/notices/{notice}/participants', [NoticeController::class, 'participants'])->name('notices.participants');
        Route::get('/notices/{notice}/participants/export', [NoticeController::class, 'exportParticipants'])->name('notices.participants.export');
    });

    // Public Events
    Route::get('/events/{notice}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{notice}/register', [EventController::class, 'register'])->name('events.register');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
