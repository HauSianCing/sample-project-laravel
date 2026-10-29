<?php

use App\Http\Middleware\CheckForEmployeeAuthenticated;
use App\Http\Middleware\LanguageChangeMiddleware;
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
            'empCheck' => CheckForEmployeeAuthenticated::class,
            'lang' => LanguageChangeMiddleware::class,
        ]);

        // Guests hitting a protected page go to the employee login screen.
        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
