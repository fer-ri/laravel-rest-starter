<?php

namespace App\Transformers;

use App\Models\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    public function transform(Permission $permission)
    {
        return [
            'uuid' => $permission->uuid,
            // 
            'createdAt' => $permission->created_at->__toString(),
            'updatedAt' => $permission->updated_at->__toString(),
        ];
    }
}
