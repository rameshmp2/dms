<?php
// app/Providers/RepositoryServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Document Repository
        $this->app->bind(
            \App\Repositories\Contracts\DocumentRepositoryInterface::class,
            \App\Repositories\Eloquent\DocumentRepository::class
        );

        // Category Repository
        $this->app->bind(
            \App\Repositories\Contracts\CategoryRepositoryInterface::class,
            \App\Repositories\Eloquent\CategoryRepository::class
        );

        // Activity Log Repository (if created)
        $this->app->bind(
            \App\Repositories\Contracts\ActivityLogRepositoryInterface::class,
            \App\Repositories\Eloquent\ActivityLogRepository::class
        );
    }

    public function boot()
    {
        //
    }
}