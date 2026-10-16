<?php

use App\Http\Middleware\ahmed;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            "ahmed" => \App\Http\Middleware\ahmed::class,
            "CheckAppExpiry" => \App\Http\Middleware\CheckAppExpiry::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'app/*/mobile-api/*',
            'mobile-api/*',
            'api/zkt/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
