<?php

namespace App\Traits;

use App\Repositories\PermissionRepository;

trait PermissionTrait
{
    /**
     * Get mutator for the "permissions" attribute.
     *
     * @param  mixed $permissions
     * @return array
     */
    public function getPermissionsAttribute($permissions)
    {
        return $permissions ? json_decode($permissions, true) : [];
    }

    /**
     * Set mutator for the "permissions" attribute.
     *
     * @param  mixed $permissions
     * @return void
     */
    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = $permissions ? json_encode($permissions) : '';
    }

    /**
     * Set (add/update) single permission value for given model.
     *
     * @param  string $permission
     * @param  bool   $value
     * @return bool
     */
    public function setPermission($permission, $value = true)
    {
        $permissions = $this->permissions;

        $this->permissions = array_set($permissions, $permission, $value);

        return $this->save();
    }

    /**
     * Unset/remove single permission value for given model.
     *
     * @param  string $permission
     * @param  bool   $value
     * @return bool
     */
    public function unsetPermission($permission)
    {
        $permissions = $this->permissions;

        array_pull($permissions, $permission);

        $this->permissions = $permissions;

        return $this->save();
    }

    /**
     * Delete all permissions attribute from given model.
     *
     * @return void
     */
    public function clearPermissions()
    {
        $this->permissions = [];

        $this->save();
    }

    /**
     * Helper for get role permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function rolesPermissions()
    {
        $repository = app()->make(PermissionRepository::class);

        $all = collect($repository->all())->values()->flatten(1)->keys();

        $permissions = [];

        foreach ($all as $name) {
            $permissions = array_merge($permissions, [$name => false]);
        }

        $roles = $this->roles->sortBy('level');

        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $role->permissions);
        }

        return $permissions;
    }

    public function getAllPermissions()
    {
        $permissions = $this->permissions;

        if ($this instanceof \App\Models\User) {
            $permissions = array_merge($this->rolesPermissions(), $permissions);
        }

        return $permissions;
    }

    /**
     * Check if has permission for given model.
     *
     * @param  string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $permissions = $this->getAllPermissions();

        foreach ($permissions as $key => $value) {
            if ($key === $permission && $value) {
                return true;
            }
        }

        return false;
    }
}
