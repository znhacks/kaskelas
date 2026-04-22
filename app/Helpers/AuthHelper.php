<?php

namespace App\Helpers;

class AuthHelper
{
    /**
     * Check if the authenticated user is an admin
     *
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Check if the authenticated user is a regular user
     *
     * @return bool
     */
    public static function isUser(): bool
    {
        return auth()->check() && auth()->user()->role === 'user';
    }

    /**
     * Check if the authenticated user has a specific role
     *
     * @param string $role
     * @return bool
     */
    public static function hasRole(string $role): bool
    {
        return auth()->check() && auth()->user()->role === $role;
    }

    /**
     * Check if the authenticated user has any of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public static function hasAnyRole(array $roles): bool
    {
        return auth()->check() && in_array(auth()->user()->role, $roles);
    }

    /**
     * Get all available roles
     *
     * @return array
     */
    public static function getRoles(): array
    {
        return ['admin', 'user'];
    }

    /**
     * Get the current user's role
     *
     * @return string|null
     */
    public static function userRole(): ?string
    {
        return auth()->user()?->role;
    }

    /**
     * Check if user can perform an action (can be extended based on permissions)
     *
     * @param string $action
     * @return bool
     */
    public static function can(string $action): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        // Define permissions based on role
        $permissions = [
            'admin' => ['manage-users', 'manage-kas', 'view-reports', 'view-dashboard', 'manage-admin'],
            'user' => ['view-dashboard', 'view-reports', 'view-riwayat'],
        ];

        $userPermissions = $permissions[$user->role] ?? [];

        return in_array($action, $userPermissions);
    }
}
