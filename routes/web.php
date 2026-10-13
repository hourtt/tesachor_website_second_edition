<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//* New Admin Controllers (App\Http\Controllers\Admin\)
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ProvinceController as AdminProvince;
use App\Http\Controllers\Admin\DestinationController as AdminDestination;
use App\Http\Controllers\Admin\UserController as AdminUser;

//* User Controllers
use App\Http\Controllers\Users\UserDashboardController;

//* Guest Controllers
use App\Http\Controllers\Guests\GuestDashboardController;
use App\Http\Controllers\Guests\GuestDestinationController;
use App\Http\Controllers\Guests\GuestProvinceController;

//* Auth Controllers
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Users\UserDestinationController;
use App\Http\Controllers\Users\UserProvinceController;
use App\Http\Controllers\ProfileAvatarController;


// GUEST ROUTES (View Only — No Login Required)
Route::name('guests.')->group(function () {
    Route::get('/', [GuestDashboardController::class, 'index'])->name('dashboard');
    Route::resource('provinces', GuestProvinceController::class);
    Route::resource('destinations', GuestDestinationController::class);
    Route::get('/destinations/{destination:slug}', [GuestDestinationController::class, 'show'])
        ->name('destinations.show');
});

// USER ROUTES (View Only — Login Required, non-admin)
Route::middleware(['user', 'prevent-back-history'])->prefix('user')->name('users.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('provinces', UserProvinceController::class);
    Route::resource('destinations', UserDestinationController::class);
    Route::get('/destinations/{destination:slug}', [UserDestinationController::class, 'show'])
        ->name('destinations.show');
});

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
