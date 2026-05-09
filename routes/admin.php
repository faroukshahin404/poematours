<?php

use App\Http\Controllers\Dashboard\Admin\ActivityController;
use App\Http\Controllers\Dashboard\Admin\BlogCategoryController;
use App\Http\Controllers\Dashboard\Admin\BlogController;
use App\Http\Controllers\Dashboard\Admin\BoatController;
use App\Http\Controllers\Dashboard\Admin\ContactSettingsController;
use App\Http\Controllers\Dashboard\Admin\CountryController;
use App\Http\Controllers\Dashboard\Admin\CrmContactController;
use App\Http\Controllers\Dashboard\Admin\CrmServiceController;
use App\Http\Controllers\Dashboard\Admin\CurrencyController;
use App\Http\Controllers\Dashboard\Admin\CustomizeTourRequestController;
use App\Http\Controllers\Dashboard\Admin\DashboardController;
use App\Http\Controllers\Dashboard\Admin\DestinationController;
use App\Http\Controllers\Dashboard\Admin\GeneralSettingsController;
use App\Http\Controllers\Dashboard\Admin\HotelController;
use App\Http\Controllers\Dashboard\Admin\HotelRoomController;
use App\Http\Controllers\Dashboard\Admin\LanguageController;
use App\Http\Controllers\Dashboard\Admin\LoginController;
use App\Http\Controllers\Dashboard\Admin\NotificationCenterController;
use App\Http\Controllers\Dashboard\Admin\PackageCategoryController;
use App\Http\Controllers\Dashboard\Admin\PackageInclusionController;
use App\Http\Controllers\Dashboard\Admin\PackageLabelController;
use App\Http\Controllers\Dashboard\Admin\PackageLabelGroupController;
use App\Http\Controllers\Dashboard\Admin\PackageReviewController;
use App\Http\Controllers\Dashboard\Admin\PageContentController;
use App\Http\Controllers\Dashboard\Admin\PageVisitController;
use App\Http\Controllers\Dashboard\Admin\PaymentSettingsController;
use App\Http\Controllers\Dashboard\Admin\ReelController;
use App\Http\Controllers\Dashboard\Admin\ReelVideoUploadController;
use App\Http\Controllers\Dashboard\Admin\ReservationController;
use App\Http\Controllers\Dashboard\Admin\ReservationQuestionController;
use App\Http\Controllers\Dashboard\Admin\SeoSettingsController;
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
        Route::get('page-visits', [PageVisitController::class, 'index'])->name('page-visits.index');
        Route::resource('languages', LanguageController::class)->except(['show']);
        Route::resource('currencies', CurrencyController::class)->except(['show']);
        Route::resource('countries', CountryController::class)->except(['show']);
        Route::resource('crm-contacts', CrmContactController::class)->parameters(['crm-contacts' => 'crmContact'])->except(['show']);
        Route::put('crm-contacts/{crmContact}/status', [CrmContactController::class, 'updateStatus'])->name('crm-contacts.status.update');
        Route::put('crm-contacts/{crmContact}/archive', [CrmContactController::class, 'updateArchiveState'])->name('crm-contacts.archive.update');
        Route::get('crm-contacts-export', [CrmContactController::class, 'export'])->name('crm-contacts.export');
        Route::resource('crm-services', CrmServiceController::class)->parameters(['crm-services' => 'crmService'])->except(['show']);
        Route::resource('destinations', DestinationController::class)->except(['show']);
        Route::resource('package-categories', PackageCategoryController::class)->except(['show']);
        Route::resource('package-label-groups', PackageLabelGroupController::class)->except(['show']);
        Route::resource('package-labels', PackageLabelController::class)->except(['show']);
        Route::resource('package-inclusions', PackageInclusionController::class)
            ->parameters(['package-inclusions' => 'package_inclusion'])
            ->except(['show']);
        Route::resource('hotels', HotelController::class)->except(['show']);
        Route::resource('hotels.rooms', HotelRoomController::class)->except(['show']);
        Route::resource('boats', BoatController::class)->except(['show']);
        Route::resource('activities', ActivityController::class)->except(['show']);
        Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);
        Route::resource('blogs', BlogController::class)->except(['show']);
        Route::resource('reservation-questions', ReservationQuestionController::class)->except(['show']);
        Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
        Route::put('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status.update');
        Route::get('reservations/{reservation}/receipt', [ReservationController::class, 'receipt'])->name('reservations.receipt');
        Route::post('reels/upload-video', ReelVideoUploadController::class)->name('reels.upload-video');
        Route::resource('reels', ReelController::class)->except(['show']);
        Route::post('packages/{package}/duplicate', [TravelPackageController::class, 'duplicate'])->name('packages.duplicate');
        Route::resource('packages.package-reviews', PackageReviewController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->scoped();
        Route::resource('packages', TravelPackageController::class)->except(['show']);
        Route::get('customize-requests', [CustomizeTourRequestController::class, 'index'])->name('customize-requests.index');
        Route::get('pages/{page}/sections/{section}/edit', [PageContentController::class, 'editSection'])
            ->name('pages.sections.edit');
        Route::put('pages/{page}/sections/{section}', [PageContentController::class, 'updateSection'])
            ->name('pages.sections.update');
        Route::resource('pages', PageContentController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::get('settings/general', [GeneralSettingsController::class, 'edit'])->name('settings.general.edit');
        Route::put('settings/general', [GeneralSettingsController::class, 'update'])->name('settings.general.update');
        Route::get('settings/contact', [ContactSettingsController::class, 'edit'])->name('settings.contact.edit');
        Route::put('settings/contact', [ContactSettingsController::class, 'update'])->name('settings.contact.update');
        Route::get('settings/payment', [PaymentSettingsController::class, 'edit'])->name('settings.payment.edit');
        Route::put('settings/payment', [PaymentSettingsController::class, 'update'])->name('settings.payment.update');
        Route::get('settings/seo', [SeoSettingsController::class, 'edit'])->name('settings.seo.edit');
        Route::put('settings/seo', [SeoSettingsController::class, 'update'])->name('settings.seo.update');
        Route::post('notifications/{notification}/read', [NotificationCenterController::class, 'markAsRead'])
            ->name('notifications.read');
        Route::post('notifications/read-all', [NotificationCenterController::class, 'markAllAsRead'])
            ->name('notifications.read-all');
    });
});
