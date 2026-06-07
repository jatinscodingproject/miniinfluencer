<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Contracts\ProfileProviderInterface;
// use App\Services\MockProfileProvider;
// use App\Services\ApifyService;
use App\Services\ApifyService;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProfileProviderInterface::class,
            // MockProfileProvider::class
            ApifyService::class

        );

        /*
        For real API later:

        $this->app->bind(
            ProfileProviderInterface::class,
            ApifyService::class
        );
        */
    }

    public function boot(): void
    {
        //
    }
}