<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Liaison PropertyRepository
        $this->app->bind(
            \App\Repositories\PropertyRepositoryInterface::class,
            \App\Repositories\EloquentPropertyRepository::class
        );

        // Liaison UserRepository
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\EloquentUserRepository::class
        );
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
