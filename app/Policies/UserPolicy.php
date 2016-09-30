<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends AbstractPolicy
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
        if ($user->isSuperAdmin() && ! in_array($ability, ['destroy'])) {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'User';
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
    {
        return [
            'user:index' => 'Listing all users',
            'user:create' => 'Create new user',
            'user:show' => 'Show detail of user',
            'user:update' => 'Update detail of user',
            'user:destroy' => 'Delete user',
        ];
    }

    /**
     * Determine if user can be listed by the user.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function index(User $user)
    {
        //
    }

    /**
     * Determine if user can be created by the user.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function store(User $user)
    {
        //
    }

    /**
     * Determine if the given {{ modelInstance }} can be showed by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $givenUser
     * @return bool
     */
    public function show(User $user, User $givenUser)
    {
        //
    }

    /**
     * Determine if the given {{ modelInstance }} can be updated by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $givenUser
     * @return bool
     */
    public function update(User $user, User $givenUser)
    {
        //
    }

    /**
     * Determine if the given {{ modelInstance }} can be deleted by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $givenUser
     * @return bool
     */
    public function destroy(User $user, User $givenUser)
    {
        if ($user->isSuperAdmin() && $user->id !== $givenUser->id) {
            return true;
        }

        if (! $user->isSuperAdmin() && $givenUser->isSuperAdmin()) {
            return false;
        }

        if (! $user->hasPermission('user:destroy')) {
            return false;
        }

        return $user->id !== $givenUser->id;
    }
}
