<?php

use App\Http\Controllers\FronEnd\HomeController;
use App\Http\Controllers\FronEnd\PackageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about.us');
Route::get('/our-journeys', [HomeController::class, 'ourJourneys'])->name('our.journeys');
Route::get('/our-journeys/{slug}', [HomeController::class, 'ourJourneyDetails'])->name('our.journeys.show');
Route::get('/terms-of-use', [HomeController::class, 'termsOfUse'])->name('terms.of.use');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/gallery', [PackageController::class, 'gallery'])->name('packages.gallery');
Route::get('/packages/{slug}', [PackageController::class, 'show'])->name('packages.show');
Route::get('/packages/{slug}/reviews', [PackageController::class, 'reviews'])->name('packages.reviews');
Route::get('/packages/{slug}/book', [PackageController::class, 'book'])->name('packages.book');
