<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Mail\ZeptoMailTransport;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Mail::extend('zeptomail', function () {
            $config = config('zeptomail');

            return new ZeptoMailTransport(
                new Client(),
                $config['api_key'],
                $config['mailbox']
            );
        });
    }
}
