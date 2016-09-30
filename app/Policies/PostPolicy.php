<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy extends AbstractPolicy
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
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Post';
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
    {
        return [
            'post:index' => 'Listing all posts',
            'post:create' => 'Create new post',
            'post:show' => 'Show detail of post',
            'post:update' => 'Update detail of post',
            'post:destroy' => 'Delete post',
        ];
    }

    /**
     * Determine if post can be listed by the user.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('post:index');
    }

    /**
     * Determine if post can be created by the user.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function store(User $user)
    {
        return $user->hasPermission('post:store');
    }

    /**
     * Determine if the given post can be showed by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Post $post
     * @return bool
     */
    public function show(User $user, Post $post)
    {
        return $user->hasPermission('post:show');
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Post $post
     * @return bool
     */
    public function update(User $user, Post $post)
    {
        if (! $user->hasPermission('post:update')) {
            return false;
        }

        return $user->id == $post->user->id;
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Post $post
     * @return bool
     */
    public function destroy(User $user, Post $post)
    {
        if (! $user->hasPermission('post:destroy')) {
            return false;
        }

        return $user->id == $post->user->id;
    }
}
