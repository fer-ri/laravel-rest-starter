<?php

use Illuminate\Contracts\Auth\Access\Gate;

if (! function_exists('mailer')) {
    function mailer()
    {
        return app()->make(App\Services\MailerService::class);
    }
}

if (! function_exists('gate')) {
    /**
     * Gate helper for authorization.
     *
     * @param  string                                      $ability
     * @param  array                                       $arguments
     * @return \Illuminate\Contracts\Auth\Access\Gate|bool
     */
    function gate($ability = null, $arguments = [])
    {
        if (is_null($ability)) {
            return app(Gate::class);
        }

        return app(Gate::class)->allows($ability, $arguments);
    }
}
