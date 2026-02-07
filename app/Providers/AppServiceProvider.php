<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\DateTimeHelper;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Blade directive for datetime
        Blade::directive('userDateTime', function ($expression) {
            return "<?php echo \App\Helpers\DateTimeHelper::userDateTime($expression); ?>";
        });

        // Blade directive for relative time
        Blade::directive('timeAgo', function ($expression) {
            return "<?php echo \App\Helpers\DateTimeHelper::userDiffForHumans($expression); ?>";
        });

        // Blade directive for date only
        Blade::directive('userDate', function ($expression) {
            return "<?php echo \App\Helpers\DateTimeHelper::userDate($expression); ?>";
        });
    }
}