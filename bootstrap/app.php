<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {

            // API ROUTES
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // ADMIN ROUTES (REGISTER FIRST)
            Route::middleware(['web', 'auth', 'verified', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // WEB ROUTES (REGISTER LAST)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
