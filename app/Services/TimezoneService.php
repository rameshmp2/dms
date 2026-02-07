<?php
// app/Services/TimezoneService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TimezoneService
{
    /**
     * Detect timezone from IP address
     */
    public function detectFromIp($ipAddress)
    {
        // Use cache to avoid repeated API calls
        $cacheKey = 'timezone_' . $ipAddress;
        
        return Cache::remember($cacheKey, 86400, function () use ($ipAddress) {
            try {
                // Using ipapi.co (free tier: 1000 requests/day)
                $response = Http::get("https://ipapi.co/{$ipAddress}/json/");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'timezone' => $data['timezone'] ?? 'UTC',
                        'country_code' => $data['country_code'] ?? null,
                        'country' => $data['country_name'] ?? null,
                        'city' => $data['city'] ?? null,
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Timezone detection failed: ' . $e->getMessage());
            }

            return [
                'timezone' => 'UTC',
                'country_code' => null,
                'country' => null,
                'city' => null,
            ];
        });
    }

    /**
     * Get list of all available timezones
     */
    public function getTimezoneList()
    {
        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        
        $timezoneList = [];
        foreach ($timezones as $timezone) {
            $timezoneList[$timezone] = $this->formatTimezone($timezone);
        }

        return $timezoneList;
    }

    /**
     * Format timezone for display
     */
    private function formatTimezone($timezone)
    {
        $now = new \DateTime('now', new \DateTimeZone($timezone));
        $offset = $now->format('P');
        
        return "(GMT{$offset}) " . str_replace('_', ' ', $timezone);
    }

    /**
     * Get common timezones
     */
    public function getCommonTimezones()
    {
        return [
            'America/New_York' => '(GMT-05:00) Eastern Time (US & Canada)',
            'America/Chicago' => '(GMT-06:00) Central Time (US & Canada)',
            'America/Denver' => '(GMT-07:00) Mountain Time (US & Canada)',
            'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US & Canada)',
            'Europe/London' => '(GMT+00:00) London',
            'Europe/Paris' => '(GMT+01:00) Paris',
            'Europe/Berlin' => '(GMT+01:00) Berlin',
            'Asia/Dubai' => '(GMT+04:00) Dubai',
            'Asia/Kolkata' => '(GMT+05:30) Mumbai, Kolkata',
            'Asia/Singapore' => '(GMT+08:00) Singapore',
            'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
            'Asia/Tokyo' => '(GMT+09:00) Tokyo',
            'Australia/Sydney' => '(GMT+10:00) Sydney',
            'Pacific/Auckland' => '(GMT+12:00) Auckland',
            'Asia/Colombo' => '(GMT+05:30) Sri Lanka',
        ];
    }

    /**
     * Convert time between timezones
     */
    public function convertTimezone($datetime, $fromTimezone, $toTimezone)
    {
        $dt = new \DateTime($datetime, new \DateTimeZone($fromTimezone));
        $dt->setTimezone(new \DateTimeZone($toTimezone));
        return $dt;
    }
}