<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy extends AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Hook before to intercept all checks.
     *
     * @param  \App\Models\User $user
     * @param  string           $ability
     * @return bool|null
     */
    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin() && ! in_array($ability, ['update', 'destroy'])) {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Role';
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
    {
        return [
            'role:index' => 'Listing all roles',
            'role:create' => 'Create new role',
            'role:show' => 'Show detail of role',
            'role:update' => 'Update detail of role',
            'role:destroy' => 'Delete role',
        ];
    }

    /**
     * Determine if role can be listed by the user.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('role:index');
    }

    /**
     * Determine if role can be created by the user.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function store(User $user)
    {
        return $user->hasPermission('role:store');
    }

    /**
     * Determine if the given role can be showed by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return bool
     */
    public function show(User $user, Role $role)
    {
        return $user->hasPermission('role:show');
    }

    /**
     * Determine if the given role can be updated by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return bool
     */
    public function update(User $user, Role $role)
    {
        if ($role->name == 'super-admin') {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('role:update');
    }

    /**
     * Determine if the given role can be deleted by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return bool
     */
    public function destroy(User $user, Role $role)
    {
        if ($role->users()->count() > 0) {
            return false;
        }

        if ($role->name == 'super-admin') {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('role:destroy');
    }
}
