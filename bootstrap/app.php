<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function ($middleware) {
        // alias middleware
        $middleware->alias([
            'roleCheck' => \App\Http\Middleware\RoleCheck::class,
        ]);

        // middleware group web
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            // \App\Http\Middleware\RoleCheck::class, // optional: run on all web routes
        ]);
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();
