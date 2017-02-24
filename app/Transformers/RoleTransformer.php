<?php

namespace App\Transformers;

use App\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'uuid' => $role->uuid,
            'name' => $role->name,
            'displayName' => $role->display_name,
            'description' => $role->description,
            'permissions' => $role->permissions,
            'level' => (int) $role->level,
            'created_at' => $role->created_at->__toString(),
            'updated_at' => $role->updated_at->__toString(),
            '_authorization' => [
                'update' => gate('update', $role),
                'destroy' => gate('destroy', $role),
            ],
        ];
    }
}
