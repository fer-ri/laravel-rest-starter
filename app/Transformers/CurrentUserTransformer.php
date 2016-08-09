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
            'roles' => 'admin',
            'permissions' => [],
            'createdAt' => $user->created_at->__toString(),
        ];
    }
}
