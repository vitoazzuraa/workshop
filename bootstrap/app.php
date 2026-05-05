<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.login'   => \App\Http\Middleware\CheckLogin::class,
            'check.vendor'  => \App\Http\Middleware\CheckVendor::class,
        ]);
        // Midtrans sends POST without CSRF token
        $middleware->validateCsrfTokens(except: [
            'kantin/midtrans/callback',
            'midtrans-callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
