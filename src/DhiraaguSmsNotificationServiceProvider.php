<?php

namespace Dash8x\DhiraaguSmsNotification;

use Dash8x\DhiraaguSms\DhiraaguSms;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;

class DhiraaguSmsNotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(DhiraaguSms::class, function () {
            $config = $this->app['config']['services.dhiraagu'];
            $username = Arr::get($config, 'username');
            $password = Arr::get($config, 'password');
            $url = Arr::get($config, 'url');

            return new DhiraaguSms($username, $password, $url);
        });

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
