<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withCommands([
        App\Console\Commands\RefreshProfiles::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(
            function (
                NotFoundHttpException $e,
                $request
            ) {

                if ($request->header('X-Inertia')) {

                    return Inertia::render(
                        'errors/NotFound'
                    )
                    ->toResponse($request)
                    ->setStatusCode(404);
                }
            }
        );
        $exceptions->render(
            function (
                Throwable $e,
                $request
            ) {

                if (
                    $request->header(
                        'X-Inertia'
                    )
                ) {

                    return \Inertia\Inertia::render(
                        'errors/ServerError'
                    )
                    ->toResponse($request)
                    ->setStatusCode(500);
                }
            }
        );
    })->create();
