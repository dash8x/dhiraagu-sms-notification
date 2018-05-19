<?php

namespace Dash8x\DhiraaguSmsNotification;

use Dash8x\DhiraaguSms\DhiraaguSms;
use Illuminate\Support\ServiceProvider;

class DhiraaguSmsNotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.

        $this->app->when(DhiraaguSmsNotificationChannel::class)
            ->needs(DhiraaguSms::class)
            ->give(function () {
                return $this->app->make(DhiraaguSms::class);
            });


        $this->app->bind(DhiraaguSms::class, function () {
            $config = $this->app['config']['services.dhiraagu'];
            $username = array_get($config, 'username');
            $password = array_get($config, 'password');
            $url = array_get($config, 'url');

            return new DhiraaguSms($username, $password, $url);
        });

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('dhiraagusms', function () {
            return $this->app->make(DhiraaguSms::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['dhiraagusms'];
    }
}
