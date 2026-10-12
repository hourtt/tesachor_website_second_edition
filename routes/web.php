<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//* Admin Controllers
use App\Http\Controllers\Admins\AdminDashboardController;
use App\Http\Controllers\Admins\AdminDestinationController;
use App\Http\Controllers\Admins\AdminProvinceController;

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




// Guest ROUTES (View Only - No Login)

Route::name('guests.')->group(function () {
    //* URL: /dashboard
    Route::get('/', [GuestDashboardController::class, 'index'])->name('dashboard');

    Route::resource('provinces', GuestProvinceController::class);
    Route::resource('destinations', GuestDestinationController::class);

    Route::get('/destinations/{destination:slug}', [GuestDestinationController::class, 'show'])->name('destinations.show');
});

// USER ROUTES (View Only - Login Required)
Route::middleware(['user', 'prevent-back-history'])->prefix('user')->name('users.')->group(function () {

    //* URL: /user/dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('dashboard'); // Name: users.dashboard

    //* URL: /user/profile
    //* URL: /user/profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('provinces', UserProvinceController::class);
    Route::resource('destinations', UserDestinationController::class);

    Route::get('/destinations/{destination:slug}', [UserDestinationController::class, 'show'])->name('destinations.show');
});

// ADMIN ROUTES (Full CRUD - Admin Login Required)
Route::middleware(['admin', 'prevent-back-history'])->prefix('admin')->name('admins.')->group(function () {

    //* URL: /admin/dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard'); // Name: admins.dashboard

    //* URL: /admin/profile
    //* URL: /admin/profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //* Resources (e.g. /admin/destinations)
    Route::resource('provinces', AdminProvinceController::class);
    Route::resource('destinations', AdminDestinationController::class);
});

Route::middleware('auth')->post('/profile/avatar', [ProfileAvatarController::class, 'update'])->name('profile.avatar.update');

require __DIR__ . '/auth.php';
