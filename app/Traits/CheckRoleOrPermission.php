<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

trait CheckRoleOrPermission
{
    public function checkRoleOrPermission($roleOrPermission, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            // throw UnauthorizedException::notLoggedIn();
            session()->flash('error', 'You don\'t have permission.');
            return;
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (!Auth::guard($guard)->user()->hasAnyRole($rolesOrPermissions) && !Auth::guard($guard)->user()->hasAnyPermission($rolesOrPermissions)) {
            // throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
    }
}
