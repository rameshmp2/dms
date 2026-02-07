<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'timezone',
        'country_code',
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get user's timezone
     */
    public function getTimezone()
    {
        return $this->timezone ?? config('app.timezone');
    }

    /**
     * Convert UTC time to user's timezone
     */
    public function toUserTimezone($datetime)
    {
        return Carbon::parse($datetime)
            ->setTimezone($this->getTimezone());
    }

    /**
     * Convert user's timezone to UTC for storage
     */
    public function toUtc($datetime)
    {
        return Carbon::parse($datetime, $this->getTimezone())
            ->setTimezone('UTC');
    }

    /**
     * Format datetime for user's timezone
     */
    public function formatDateTime($datetime, $format = 'Y-m-d H:i:s')
    {
        return $this->toUserTimezone($datetime)->format($format);
    }

    /**
     * Get friendly time difference (e.g., "2 hours ago")
     */
    public function diffForHumans($datetime)
    {
        return $this->toUserTimezone($datetime)->diffForHumans();
    }
}