<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

if (! is_writable(dirname(__DIR__).'/bootstrap/cache')) {
    $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
    
    $_SERVER['APP_SERVICES_CACHE'] = '/tmp/services.php';
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
    putenv('APP_SERVICES_CACHE=/tmp/services.php');
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            return new \Illuminate\Http\Response(
                json_encode([
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                500,
                ['Content-Type' => 'application/json']
            );
        });
    })->create()
    ->useStoragePath(isset($_ENV['VERCEL']) ? '/tmp/storage' : dirname(__DIR__).'/storage');
