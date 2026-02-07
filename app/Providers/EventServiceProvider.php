<?php
// app/Providers/EventServiceProvider.php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\SetUserTimezoneOnLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            SetUserTimezoneOnLogin::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}