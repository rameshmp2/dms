<?php
// app/Helpers/DateTimeHelper.php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DateTimeHelper
{
    /**
     * Convert UTC to user timezone and format
     */
    public static function userDateTime($datetime, $format = 'M d, Y h:i A')
    {
        if (!$datetime) {
            return '-';
        }

        $timezone = Auth::check() 
            ? Auth::user()->timezone 
            : config('app.timezone');

        return Carbon::parse($datetime)
            ->setTimezone($timezone)
            ->format($format);
    }

    /**
     * Get relative time (e.g., "2 hours ago")
     */
    public static function userDiffForHumans($datetime)
    {
        if (!$datetime) {
            return '-';
        }

        $timezone = Auth::check() 
            ? Auth::user()->timezone 
            : config('app.timezone');

        return Carbon::parse($datetime)
            ->setTimezone($timezone)
            ->diffForHumans();
    }

    /**
     * Format date only
     */
    public static function userDate($datetime, $format = 'M d, Y')
    {
        return self::userDateTime($datetime, $format);
    }

    /**
     * Format time only
     */
    public static function userTime($datetime, $format = 'h:i A')
    {
        return self::userDateTime($datetime, $format);
    }
}