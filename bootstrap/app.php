<?php

use App\Http\Middleware\PetaniMiddleware;
use App\Http\Middleware\PoktanMiddleware;
use App\Http\Middleware\PPLMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append(\App\Http\Middleware\DynamicAppUrl::class);
        $middleware->alias([
            'petugas_poktan' =>PoktanMiddleware::class,
            'petugas_ppl' => PPLMiddleware::class,
            'petani' => PetaniMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
