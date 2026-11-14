<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DestinationController as AdminDestination;
use App\Http\Controllers\Admin\ProvinceController as AdminProvince;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\EditorUploadController;
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
})->middleware('throttle:public')->name('locale.switch');

// SHARED PUBLIC ROUTES (Accessible to both Guests and Authenticated Users)
Route::middleware('throttle:public')->name('public.')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::get('/provinces', [PublicProvince::class, 'index'])->name('provinces.index');
    Route::get('/provinces/{province:slug}', [PublicProvince::class, 'show'])->name('provinces.show');
    Route::get('/destinations/{destination:slug}', [PublicDestination::class, 'show'])->name('destinations.show');
});

// ADMIN ROUTES
Route::middleware(['auth', 'admin', 'prevent-back-history'])->prefix('admin')->name('admins.')->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Provinces CRUD
    Route::resource('provinces', AdminProvince::class);

    // Destinations CRUD
    Route::patch('destinations/{destination}/location', [AdminDestination::class, 'updateLocation'])->name('destinations.location.update');
    Route::resource('destinations', AdminDestination::class);

    // Uploads (admin only, throttled)
    Route::middleware('throttle:uploads')->group(function () {
        Route::post('editor/upload', EditorUploadController::class)->name('editor.upload');
        Route::post('profile/avatar', [ProfileAvatarController::class, 'update'])->name('profile.avatar.update');
        Route::delete('profile/avatar', [ProfileAvatarController::class, 'destroy'])->name('profile.avatar.destroy');
    });
});

require __DIR__ . '/auth.php';
