<?php

namespace App\Providers;

use DrewM\MailChimp\MailChimp;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class MailChimpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MailChimp::class, function (Application $app) {
            $config = $app->make('config')->get('mailchimp');
            return new MailChimp($config['api_key']);
        });
    }

}
