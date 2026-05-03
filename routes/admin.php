<?php

use App\Http\Controllers\Dashboard\Admin\ActivityController;
use App\Http\Controllers\Dashboard\Admin\BoatController;
use App\Http\Controllers\Dashboard\Admin\CurrencyController;
use App\Http\Controllers\Dashboard\Admin\DashboardController;
use App\Http\Controllers\Dashboard\Admin\DestinationController;
use App\Http\Controllers\Dashboard\Admin\CustomizeTourRequestController;
use App\Http\Controllers\Dashboard\Admin\HotelController;
use App\Http\Controllers\Dashboard\Admin\HotelRoomController;
use App\Http\Controllers\Dashboard\Admin\LanguageController;
use App\Http\Controllers\Dashboard\Admin\LoginController;
use App\Http\Controllers\Dashboard\Admin\PackageCategoryController;
use App\Http\Controllers\Dashboard\Admin\PackageLabelController;
use App\Http\Controllers\Dashboard\Admin\PackageLabelGroupController;
use App\Http\Controllers\Dashboard\Admin\PaymentSettingsController;
use App\Http\Controllers\Dashboard\Admin\PageContentController;
use App\Http\Controllers\Dashboard\Admin\ReelController;
use App\Http\Controllers\Dashboard\Admin\ReelVideoUploadController;
use App\Http\Controllers\Dashboard\Admin\TravelPackageController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [LoginController::class, 'create'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::resource('languages', LanguageController::class)->except(['show']);
        Route::resource('currencies', CurrencyController::class)->except(['show']);
        Route::resource('destinations', DestinationController::class)->except(['show']);
        Route::resource('package-categories', PackageCategoryController::class)->except(['show']);
        Route::resource('package-label-groups', PackageLabelGroupController::class)->except(['show']);
        Route::resource('package-labels', PackageLabelController::class)->except(['show']);
        Route::resource('hotels', HotelController::class)->except(['show']);
        Route::resource('hotels.rooms', HotelRoomController::class)->except(['show']);
        Route::resource('boats', BoatController::class)->except(['show']);
        Route::resource('activities', ActivityController::class)->except(['show']);
        Route::post('reels/upload-video', ReelVideoUploadController::class)->name('reels.upload-video');
        Route::resource('reels', ReelController::class)->except(['show']);
        Route::post('packages/{package}/duplicate', [TravelPackageController::class, 'duplicate'])->name('packages.duplicate');
        Route::resource('packages', TravelPackageController::class)->except(['show']);
        Route::get('customize-requests', [CustomizeTourRequestController::class, 'index'])->name('customize-requests.index');
        Route::get('settings/payments', [PaymentSettingsController::class, 'edit'])->name('settings.payments.edit');
        Route::put('settings/payments', [PaymentSettingsController::class, 'update'])->name('settings.payments.update');
        Route::get('settings/reservation-addons', [PaymentSettingsController::class, 'editReservationAddons'])->name('settings.reservation-addons.edit');
        Route::put('settings/reservation-addons', [PaymentSettingsController::class, 'updateReservationAddons'])->name('settings.reservation-addons.update');
        Route::get('pages/{page}/sections/{section}/edit', [PageContentController::class, 'editSection'])
            ->name('pages.sections.edit');
        Route::put('pages/{page}/sections/{section}', [PageContentController::class, 'updateSection'])
            ->name('pages.sections.update');
        Route::resource('pages', PageContentController::class)->only(['index', 'edit', 'update']);
    });
});
