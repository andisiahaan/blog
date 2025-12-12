<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are protected by Sanctum authentication.
| Use Personal Access Token in Authorization header: Bearer {token}
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Posts
    Route::post('/posts', [PostController::class, 'store']);

    // Tags
    Route::get('/tags', [TagController::class, 'index']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
});
