<?php

namespace App\Services\Panel\Auth;

use App\Models\Role;
use App\Models\User;

class IdentityService
{
    public function addRoleToUser($userId, $roleName)
    {
        $user = User::find($userId);
        if ($user->hasRole($roleName))
            return;

        $roleId = Role::where("name", $roleName)->select("id")->first();
        $user->roles()->attach($roleId);
    }
}
