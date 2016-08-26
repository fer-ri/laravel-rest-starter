<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Contract\Auth\Provider;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class PrivateTokenProvider implements Provider
{
    public function authenticate(Request $request, Route $route)
    {
        $token = $this->parseToken($request);

        $user = User::where('private_token', $token)->first();

        if (! $user) {
            throw new UnauthorizedHttpException('Unable to authenticate with Api Token Provider.');
        }

        return $user;
    }

    protected function parseToken(Request $request)
    {
        if (! $token = $request->headers->get('PRIVATE-TOKEN')) {
            if (! $token = $request->query('private_token', false)) {
                throw new BadRequestHttpException('The api token could not be parsed from the request');
            }
        }

        return $token;
    }
}
