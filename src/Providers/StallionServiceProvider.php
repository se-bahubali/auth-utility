<?php

namespace StallionExpress\AuthUtility\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use StallionExpress\AuthUtility\Providers\StallionAuthServiceProvider;

class StallionServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(StallionAuthServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
