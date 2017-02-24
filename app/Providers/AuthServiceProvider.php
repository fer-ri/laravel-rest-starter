<?php

namespace App\Providers;

use Validator;
use App\Policies\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Post::class => \App\Policies\PostPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Validator::extend('permissions', function ($attribute, $value, $parameters, $validator) {
            if (! is_array($value)) {
                return false;
            }

            foreach ($value as $name => $state) {
                if (! is_string($name) || ! is_bool($state)) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GateContract::class, function ($app) {
            return new Gate($app, function () use ($app) {
                return call_user_func($app['auth']->userResolver());
            });
        });
    }
}
