<?php

namespace App\Traits;

trait RoleTrait
{
    /**
     * Create belongsToMany relation.
     *
     * @return null|\Illuminate\Database\Eloquent\Collection
     */
    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class)
            ->orderBy('level', 'desc');
    }

    /**
     * Get all roles as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoles()
    {
        if (! $this->relationLoaded('roles')) {
            $this->load('roles');
        }

        return $this->getRelation('roles');
    }

    /**
     * Attach role to the given user.
     *
     * @param  int|\Illuminate\Database\Eloquent\Model $role
     * @return void
     */
    public function attachRole($role)
    {
        if (! $this->getRoles()->contains($role)) {
            $this->roles()->attach($role);

            $this->load('roles');
        }
    }

    /**
     * Detach role from the given user.
     *
     * @param  int|\Illuminate\Database\Eloquent\Model $role
     * @return void
     */
    public function detachRole($role)
    {
        $this->roles()->detach($role);

        $this->load('roles');
    }

    /**
     * Detach all roles from the given user.
     *
     * @return void
     */
    public function detachAllRoles()
    {
        $this->roles()->detach();

        $this->load('roles');
    }

    /**
     * Check if a user has role.
     *
     * @param  int|string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->getRoles()->contains(function ($value, $key) use ($role) {
            return $role == $value->id || str_is($role, $value->name);
        });
    }

    /**
     * Check if the given user has role as `super-admin`.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Get first role only.
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function getRoleAttribute()
    {
        $roles = $this->getRoles();

        if (is_null($roles)) {
            return;
        }

        return $roles->first();
    }
}
