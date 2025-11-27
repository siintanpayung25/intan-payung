<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetZonaWaktu;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Menambahkan middleware SetZonaWaktu secara global
        $middleware->append(SetZonaWaktu::class);  // Menggunakan `add()` untuk menambahkan middleware
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
