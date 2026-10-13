<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//* New Admin Controllers (App\Http\Controllers\Admin\)
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ProvinceController as AdminProvince;
use App\Http\Controllers\Admin\DestinationController as AdminDestination;
use App\Http\Controllers\Admin\UserController as AdminUser;

//* Shared Public & Frontend Controllers (App\Http\Controllers\Guests\)
use App\Http\Controllers\Guests\GuestDashboardController;
use App\Http\Controllers\Guests\GuestDestinationController;
use App\Http\Controllers\Guests\GuestProvinceController;

//* Auth Controllers
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\ProfileAvatarController;


// SHARED PUBLIC ROUTES (Accessible to both Guests and Authenticated Users)
Route::name('guests.')->group(function () {
    Route::get('/', [GuestDashboardController::class, 'index'])->name('dashboard');
    Route::get('/provinces', [GuestProvinceController::class, 'index'])->name('provinces.index');
    Route::get('/provinces/{province:slug}', [GuestProvinceController::class, 'show'])->name('provinces.show');
    Route::get('/destinations/{destination:slug}', [GuestDestinationController::class, 'show'])->name('destinations.show');
});

// AUTHENTICATED USER ROUTE ALIASES & COMPATIBILITY
Route::get('/dashboard', [GuestDashboardController::class, 'index'])->name('users.dashboard');
Route::get('/user/dashboard', fn() => redirect()->route('users.dashboard'));
Route::get('/user/profile', fn() => redirect()->route('profile.edit'))->name('users.profile.edit');

// ADMIN ROUTES (Full CRUD — Admin Role Required)
// Controllers: App\Http\Controllers\Admin\*
// Views:       resources/views/admin/*
Route::middleware(['admin', 'prevent-back-history'])->prefix('admin')->name('admins.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Provinces CRUD
    Route::resource('provinces', AdminProvince::class);

    // Destinations CRUD (full resource including index)
    Route::resource('destinations', AdminDestination::class);

    // Users management
    Route::resource('users', AdminUser::class)->only(['index', 'edit', 'update']);
});

// SHARED AUTH ROUTES
Route::middleware('auth')->group(function () {
    Route::post('/profile/avatar', [ProfileAvatarController::class, 'update'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileAvatarController::class, 'destroy'])->name('profile.avatar.destroy');
});

require __DIR__ . '/auth.php';
