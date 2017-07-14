<?php

namespace SanderVanHooft\PayableRedirect;

use Illuminate\Support\ServiceProvider;

class PayableRedirectServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish a config file
        $this->publishes([
            __DIR__.'/../config/payable.php' => config_path('payable.php'),
        ], 'config');

        // Publish migrations
            $this->publishes([
                __DIR__.'/../database/migrations/2017_05_11_163005_create_payments_table.php'
                => database_path('migrations/2017_05_11_163005_create_payments_table.php'),
            ], 'migrations');

            // Load routes
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
