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
            if (strlen($config['api_key']) === 0) {
                throw new \Exception('No MailChimp API key supplied.');
            }
            if (strpos($config['api_key'], '-') === false) {
                throw new \Exception('Invalid MailChimp API key '.$config['api_key'].' supplied.');
            }
            return new MailChimp($config['api_key']);
        });
    }

}
