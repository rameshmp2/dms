<?php
// app/Listeners/SetUserTimezoneOnLogin.php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\TimezoneService;

class SetUserTimezoneOnLogin
{
    private $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Only auto-detect if user hasn't set timezone manually
        if ($user->timezone === 'UTC' || $user->timezone === null) {
            $ipAddress = request()->ip();
            $timezoneData = $this->timezoneService->detectFromIp($ipAddress);

            $user->update([
                'timezone' => $timezoneData['timezone'],
                'country_code' => $timezoneData['country_code'],
            ]);
        }
    }
}