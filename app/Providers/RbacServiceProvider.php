<?php

namespace App\Providers;

use App\Helpers\AuthHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register helper functions as aliases for easy access
        if (!function_exists('isAdmin')) {
            function isAdmin(): bool
            {
                return AuthHelper::isAdmin();
            }
        }

        if (!function_exists('isUser')) {
            function isUser(): bool
            {
                return AuthHelper::isUser();
            }
        }

        if (!function_exists('hasRole')) {
            function hasRole(string $role): bool
            {
                return AuthHelper::hasRole($role);
            }
        }

        if (!function_exists('hasAnyRole')) {
            function hasAnyRole(array $roles): bool
            {
                return AuthHelper::hasAnyRole($roles);
            }
        }

        if (!function_exists('userRole')) {
            function userRole(): ?string
            {
                return AuthHelper::userRole();
            }
        }

        if (!function_exists('userCan')) {
            function userCan(string $action): bool
            {
                return AuthHelper::can($action);
            }
        }

        // Register Blade directives
        Blade::if('admin', function () {
            return AuthHelper::isAdmin();
        });

        Blade::if('notAdmin', function () {
            return !AuthHelper::isAdmin();
        });

        Blade::if('user', function () {
            return AuthHelper::isUser();
        });

        Blade::if('notUser', function () {
            return !AuthHelper::isUser();
        });

        Blade::if('role', function (string $role) {
            return AuthHelper::hasRole($role);
        });

        Blade::if('anyRole', function (array $roles) {
            return AuthHelper::hasAnyRole($roles);
        });

        Blade::if('can', function (string $action) {
            return AuthHelper::can($action);
        });
    }
}
