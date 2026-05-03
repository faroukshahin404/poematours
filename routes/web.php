<?php

use App\Http\Controllers\FronEnd\HomeController;
use App\Http\Controllers\FronEnd\PackageController;
use App\Http\Controllers\FronEnd\CustomizeTourRequestController;
use App\Http\Controllers\FronEnd\ReservationController;
use App\Http\Controllers\FronEnd\StripeWebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about.us');
Route::get('/destinations', [HomeController::class, 'destinations'])->name('destinations.index');
Route::get('/our-journeys', [HomeController::class, 'ourJourneys'])->name('our.journeys');
Route::get('/our-journeys/{slug}', [HomeController::class, 'ourJourneyDetails'])->name('our.journeys.show');
Route::get('/terms-of-use', [HomeController::class, 'termsOfUse'])->name('terms.of.use');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
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
Route::post('/customize', [CustomizeTourRequestController::class, 'store'])->name('customize.store');
Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/reservation/intent', [ReservationController::class, 'createPaymentIntent'])->name('reservation.intent');
Route::get('/reservation/success', [ReservationController::class, 'success'])->name('reservation.success');
Route::get('/reservation/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
Route::post('/stripe/webhook', StripeWebhookController::class)
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('stripe.webhook');
