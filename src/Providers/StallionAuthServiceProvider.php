<?php

namespace StallionExpress\AuthUtility\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use StallionExpress\AuthUtility\Guards\StallionAuthTokenGuard;

class StallionAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Auth::extend('stallionauth', function ($app, $name, array $config) {
            // automatically build the DI, put it as reference
            return new StallionAuthTokenGuard(app(StallionTokenToUserProvider::class), app('request'), $config);
        });
    }
}
