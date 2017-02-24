<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class CurrentUserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'roles',
    ];

    public function transform(User $user)
    {
        return [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->__toString(),
        ];
    }

    public function includeRoles(User $user)
    {
        $roles = $user->roles;

        return $this->collection($roles, new RoleTransformer);
    }
}
