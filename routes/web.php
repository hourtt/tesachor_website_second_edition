<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DestinationController as AdminDestination;
use App\Http\Controllers\Admin\ProvinceController as AdminProvince;
// * New Admin Controllers
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\ProfileAvatarController;
use App\Http\Controllers\Public\DestinationController as PublicDestination;
// * Shared Public Controllers
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\ProvinceController as PublicProvince;
use Illuminate\Http\Request;
// * Auth Controllers
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// LOCALE SWITCH ROUTE
Route::get('/lang/{locale}', function (Request $request, string $locale) {
    if (in_array($locale, ['en', 'km'])) {
        session(['locale' => $locale]);
    }

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['status' => 'success', 'locale' => $locale]);
    }

    return redirect()->back(fallback: route('public.landing'));
})->name('locale.switch');

// SHARED PUBLIC ROUTES (Accessible to both Guests and Authenticated Users)
Route::name('public.')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::get('/provinces', [PublicProvince::class, 'index'])->name('provinces.index');
    Route::get('/provinces/{province:slug}', [PublicProvince::class, 'show'])->name('provinces.show');
    Route::get('/destinations/{destination:slug}', [PublicDestination::class, 'show'])->name('destinations.show');
});

// ADMIN ROUTES
Route::middleware(['admin', 'prevent-back-history'])->prefix('admin')->name('admins.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Provinces CRUD
    Route::resource('provinces', AdminProvince::class);

    // Destinations CRUD
    Route::resource('destinations', AdminDestination::class);

    // Users management
    Route::resource('users', AdminUser::class)->only(['index', 'edit', 'update']);
});

// SHARED AUTH ROUTES
Route::middleware('auth')->group(function () {
    Route::post('/profile/avatar', [ProfileAvatarController::class, 'update'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileAvatarController::class, 'destroy'])->name('profile.avatar.destroy');
});

require __DIR__.'/auth.php';
