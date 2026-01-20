<?php

use App\Http\Controllers\Admin\AiLogController;
use App\Http\Controllers\Admin\AiSettingController;
use App\Http\Controllers\Admin\AiTopicController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Profile
Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        
Route::resource('posts', PostController::class);
Route::resource('pages', PageController::class);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('users', UserController::class);

// API Tokens
Route::get('tokens', [TokenController::class, 'index'])->name('tokens.index');
Route::post('tokens', [TokenController::class, 'store'])->name('tokens.store');
Route::delete('tokens/{token}', [TokenController::class, 'destroy'])->name('tokens.destroy');

// Settings
Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
Route::put('settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');
Route::get('settings/custom-tags', [SettingController::class, 'customTags'])->name('settings.custom-tags');
Route::put('settings/custom-tags', [SettingController::class, 'updateCustomTags'])->name('settings.custom-tags.update');

// AI Auto Blog
Route::prefix('ai')->name('ai.')->group(function () {
    // AI Settings (read-only from .env)
    Route::get('settings', [AiSettingController::class, 'index'])->name('settings');
    Route::post('settings/test', [AiSettingController::class, 'testConnection'])->name('settings.test');
    Route::post('settings/thumbnails', [AiSettingController::class, 'uploadThumbnails'])->name('settings.thumbnails.upload');
    Route::delete('settings/thumbnails', [AiSettingController::class, 'deleteThumbnail'])->name('settings.thumbnails.delete');

    // AI Topics
    Route::resource('topics', AiTopicController::class);
    Route::post('topics/{topic}/generate', [AiTopicController::class, 'generate'])->name('topics.generate');

    // AI Logs
    Route::get('logs', [AiLogController::class, 'index'])->name('logs.index');
    Route::get('logs/stats', [AiLogController::class, 'stats'])->name('logs.stats');
    Route::get('logs/{log}', [AiLogController::class, 'show'])->name('logs.show');
    Route::post('logs/{log}/retry', [AiLogController::class, 'retry'])->name('logs.retry');
    Route::delete('logs/cleanup', [AiLogController::class, 'cleanup'])->name('logs.cleanup');
});

