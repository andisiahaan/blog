<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
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

Route::prefix('admin')
    ->middleware(['web', 'auth', 'verified', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
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
    });



