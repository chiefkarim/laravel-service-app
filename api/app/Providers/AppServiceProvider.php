<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // manage all permissions for resource (users service requests services ... )
        // user permissions
        Gate::define('has-permission', function (User $user, string $resource, string $operation) {
            return $user->permissions()
                ->where('resource', $resource)
                ->where('operation', $operation)
                ->exists();
        });

    }
}
