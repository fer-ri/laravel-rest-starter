<?php

namespace App\Providers;

use Dingo\Api\Exception\Handler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app(Handler::class)->register(function (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Resource not found!');
        });

        app(Handler::class)->register(function (TokenBlacklistedException $e) {
            throw new UnauthorizedHttpException(null, $e->getMessage());
        });

        app(Handler::class)->register(function (AuthorizationException $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
