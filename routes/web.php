<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Blog Frontend Routes
Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tag/{tag:slug}', [TagController::class, 'show'])->name('tag.show');

Route::get('/search', SearchController::class)->name('search');

// Pages
Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');

// Sitemap Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-tags.xml', [SitemapController::class, 'tags'])->name('sitemap.tags');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-posts-{page}.xml', [SitemapController::class, 'posts'])->where('page', '[0-9]+')->name('sitemap.posts');

// Guest Routes (Auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Email Verification
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::get('/{post:slug}', [PostController::class, 'show'])
    ->where('post', '^(?!admin$|login$|register$|api$|pages$|posts$).+')
    ->name('post.show');
