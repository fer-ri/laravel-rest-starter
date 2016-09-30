<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Gate;

class PermissionRepository
{
    /**
     * Get all defined policy methods.
     *
     * @return array
     */
    public function all()
    {
        $permissions = [];

        $policies = Gate::getPolicies();

        foreach ($policies as $model => $policy) {
            $newPolicy = new $policy;

            $permissions[$newPolicy->getName()] = $newPolicy->getPermissions();
        }

        return $permissions;
    }
}
