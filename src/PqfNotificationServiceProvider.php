<?php

namespace Pqf\Notification;

use Illuminate\Support\ServiceProvider;
use Pqf\Smscode\Sms;

class PqfNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/pqfno.php' => config_path('pqfno.php')]);
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'pqfno');
        $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang/vendor/pqfno')],'sms');

    }

    public function register()
    {
        $this->app->singleton('pqf_notification', function () {
            return new Notification();
        });
    }
}