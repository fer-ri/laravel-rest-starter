<?php

namespace App\Listeners;

class AuthEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        //
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        //
    }

    /**
     * Handle user register events.
     */
    public function onUserRegister($event)
    {
        $user = $event->user;

        mailer()->sendEmailActivationForUser($user);
    }

    /**
     * Handle user activate events.
     */
    public function onUserActivate($event)
    {
        //
    }

    /**
     * Handle token refresh events.
     */
    public function onTokenRefresh($event)
    {
        //
    }

    /**
     * Handle password recovery events.
     */
    public function onPasswordRecovery($event)
    {
        //
    }

    /**
     * Handle password reset events.
     */
    public function onPasswordReset($event)
    {
        //
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Auth\UserLoggedIn',
            'App\Listeners\AuthEventListener@onUserLogin'
        );

        $events->listen(
            'App\Events\Auth\UserLoggedOut',
            'App\Listeners\AuthEventListener@onUserLogout'
        );

        $events->listen(
            'App\Events\Auth\UserRegistered',
            'App\Listeners\AuthEventListener@onUserRegister'
        );

        $events->listen(
            'App\Events\Auth\UserActivated',
            'App\Listeners\AuthEventListener@onUserActivate'
        );

        $events->listen(
            'App\Events\TokenRefreshed',
            'App\Listeners\AuthEventListener@onTokenRefresh'
        );

        $events->listen(
            'App\Events\Auth\PasswordAskForRecovery',
            'App\Listeners\AuthEventListener@onPasswordRecovery'
        );

        $events->listen(
            'App\Events\Auth\PasswordReset',
            'App\Listeners\AuthEventListener@onPasswordReset'
        );
    }
}
