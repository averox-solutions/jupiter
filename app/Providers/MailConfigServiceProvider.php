<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (file_exists(storage_path('installed'))) {
            config(['mail.driver' => getSetting('MAIL_MAILER')]);
            config(['mail.host' => getSetting('MAIL_HOST')]);
            config(['mail.port' => getSetting('MAIL_PORT')]);
            config(['mail.username' => getSetting('MAIL_USERNAME')]);
            config(['mail.password' => getSetting('MAIL_PASSWORD')]);
            config(['mail.encryption' => getSetting('MAIL_ENCRYPTION')]);
            config(['mail.from.address' => getSetting('MAIL_FROM_ADDRESS')]);
            config(['mail.from.name' => getSetting('APPLICATION_NAME')]);
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
