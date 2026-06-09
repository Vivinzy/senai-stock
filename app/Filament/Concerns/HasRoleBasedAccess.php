<?php

namespace App\Filament\Concerns;

trait HasRoleBasedAccess
{
    protected static function hasRole(string $role): bool
    {
        return auth()->check() && auth()->user()->role === $role;
    }

    protected static function hasAnyRole(array $roles): bool
    {
        return auth()->check() && in_array(auth()->user()->role, $roles, true);
    }
}
