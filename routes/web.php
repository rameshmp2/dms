<?php
// routes/web.php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;


// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    // Register
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
    // Profile routes
    Route::get('profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/detect-timezone', [UserProfileController::class, 'detectTimezone'])
        ->name('profile.detect-timezone');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Documents
    Route::resource('documents', DocumentController::class);
    Route::get('documents/{id}/download', [DocumentController::class, 'download'])
        ->name('documents.download');
    
    // Categories
    Route::resource('categories', CategoryController::class);
});
// Redirect root to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});