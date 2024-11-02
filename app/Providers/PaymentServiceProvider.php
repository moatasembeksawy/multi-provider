<?php

namespace App\Providers;

use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use App\Services\PaymentProviders\DataProviderX;
use App\Services\PaymentProviders\DataProviderY;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService([
                new DataProviderX(),
                new DataProviderY()
            ]);
        });
    }
}
