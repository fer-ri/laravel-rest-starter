<?php

namespace App\Providers;

use Dingo\Api\Exception\Handler;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
