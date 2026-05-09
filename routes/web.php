<?php

use App\Http\Controllers\FronEnd\ContactLeadController;
use App\Http\Controllers\FronEnd\CustomizeTourRequestController;
use App\Http\Controllers\FronEnd\HomeController;
use App\Http\Controllers\FronEnd\PackageController;
use App\Http\Controllers\FronEnd\ReservationController;
use App\Http\Controllers\FronEnd\SitemapController;
use App\Http\Controllers\FronEnd\StripePaymentController;
use App\Http\Middleware\TrackPageVisit;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::middleware(TrackPageVisit::class)->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about.us');
    Route::get('/destinations', [HomeController::class, 'destinations'])->name('destinations.index');
    Route::get('/our-journeys', [HomeController::class, 'ourJourneys'])->name('our.journeys');
    Route::get('/our-journeys/{slug}', [HomeController::class, 'ourJourneyDetails'])->name('our.journeys.show');
    Route::get('/terms-of-use', [HomeController::class, 'termsOfUse'])->name('terms.of.use');
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::get('/pages/{page:slug}', [HomeController::class, 'dynamicPage'])->name('pages.show');
    Route::get('/login', [HomeController::class, 'login'])->name('login');
    Route::get('/register', [HomeController::class, 'register'])->name('register');
    Route::get('/forgot-password', [HomeController::class, 'forgotPassword'])->name('password.request');
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('/search', [PackageController::class, 'search'])->name('search');
    Route::get('/activities/{activity}', [PackageController::class, 'activities'])->name('activities.show');
    Route::get('/packages/gallery', [PackageController::class, 'gallery'])->name('packages.gallery');
    Route::get('/packages/{slug}', [PackageController::class, 'show'])->name('packages.show');
    Route::get('/packages/{slug}/reviews', [PackageController::class, 'reviews'])->name('packages.reviews');
    Route::get('/packages/{slug}/book', [PackageController::class, 'book'])->name('packages.book');
    Route::get('/customize', [CustomizeTourRequestController::class, 'create'])->name('customize.create');
    Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation.create');
    Route::get('/payment/success', [StripePaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure', [StripePaymentController::class, 'failure'])->name('payment.failure');
});

Route::post('/customize', [CustomizeTourRequestController::class, 'store'])->name('customize.store');
Route::post('/contact-leads/website', [ContactLeadController::class, 'storeWebsite'])->name('contact-leads.website.store');
Route::post('/contact-leads/newsletter', [ContactLeadController::class, 'storeNewsletter'])->name('contact-leads.newsletter.store');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
Route::post('/payment/stripe/webhook', [StripePaymentController::class, 'webhook'])->name('payment.stripe.webhook');
