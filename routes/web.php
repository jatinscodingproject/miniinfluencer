<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;

Route::redirect('/', '/profiles');

Route::resource(
    'profiles',
    ProfileController::class
);

Route::post(
    '/webhooks/apify',
    [WebhookController::class, 'handle']
);

Route::get('/healthz', function () {
    try {
        DB::connection()->getPdo();
        Redis::ping();
        return response()->json([
            'status' => 'ok',
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'degraded',
            'error' => $e->getMessage(),
        ], 503);
    }
});

Route::fallback(function () {
    abort(404);
});


