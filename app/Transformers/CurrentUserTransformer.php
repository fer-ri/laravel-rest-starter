<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class CurrentUserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ? $user->role->name : null,
            'permissions' => $user->getAllPermissions(),
            'createdAt' => $user->created_at->__toString(),
        ];
    }
}
