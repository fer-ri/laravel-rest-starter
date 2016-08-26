<?php

namespace App\Http\Controllers\API;

use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Password;
use GuzzleHttp\Exception\RequestException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Transformers\CurrentUserTransformer;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends APIController
{
    public function login(LoginRequest $request)
    {
        // if we use username, then just change `email` to `username`
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                throw new UnauthorizedHttpException(str_random(32), trans('auth.invalid_credentials'));
            }

            $user = JWTAuth::authenticate($token);

            if (! $user->activated_at) {
                JWTAuth::invalidate($token);

                throw new UnauthorizedHttpException(str_random(32), trans('auth.user_not_activated'));
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            throw new HttpException(trans('auth.could_not_create_token'));
        }

        event(new \App\Events\Auth\UserLoggedIn($user));

        return $this->response->array(compact('token'));
    }

    public function LoginWithFacebook(Request $request, UserRepository $userRepository)
    {
        $this->validate($request, [
            'access_token' => 'required',
        ]);

        $accesToken = $request->get('access_token');

        try {
            $providerUser = Socialite::driver('facebook')->userFromToken($accesToken);
        } catch (RequestException $e) {
            throw new UnauthorizedHttpException($accesToken, $e->getMessage());
        }

        $user = $userRepository->findOrCreateSocialAccount($providerUser, 'facebook');

        $token = JWTAuth::fromUser($user);

        event(new \App\Events\Auth\UserLoggedIn($user));

        event(new \App\Events\Auth\UserLoggedInWithFacebook($user));

        return $this->response->array(compact('token'));
    }

    public function validateToken()
    {
        // Our routes file should have already authenticated this token
        // so we just return success here
        return $this->response->noContent();
    }

    public function refreshToken()
    {
        $currentToken = JWTAuth::getToken();

        try {
            $token = JWTAuth::refresh($currentToken);

            $user = JWTAuth::toUser($token);

            if ($user) {
                event(new \App\Events\Auth\TokenRefreshed($user));
            }

            return $this->response->array(compact('token'));
        } catch (TokenBlacklistedException $e) {
            throw new UnauthorizedHttpException($currentToken, $e->getMessage());
        }
    }

    public function register(RegisterRequest $request, UserRepository $userRepository)
    {
        $newUser = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'activation_code' => str_random(32),
        ];

        $user = $userRepository->create($newUser);

        event(new \App\Events\Auth\UserRegistered($user));

        return $this->response->item($user, new UserTransformer);
    }

    public function activate(Request $request, UserRepository $userRepository)
    {
        $this->validate($request, [
            'activation_code' => 'required',
        ]);

        $user = $userRepository->findForActivate($request->get('activation_code'));

        if (! $user) {
            throw new UnauthorizedHttpException(
                $request->get('activation_code'),
                'Invalid activation code or user already activated'
            );
        }

        $user->activated_at = date('Y-m-d H:i:s');

        $user->save();

        event(new \App\Events\Auth\UserActivated($user));

        $token = JWTAuth::fromUser($user);

        return $this->response->array(compact('token'));
    }

    public function recovery(Request $request, UserRepository $userRepository)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->from(env('MAIL_SENDER'));
            $message->subject('Password recovery');
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                $user = $userRepository->findByEmail($request->only('email'));

                event(new \App\Events\Auth\PasswordAskForRecovery($user));

                return $this->response->noContent();
            case Password::INVALID_USER:
                return $this->response->errorNotFound();
        }
    }

    public function reset(Request $request, UserRepository $userRepository)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                $user = $userRepository->findByEmail($request->only('email'));

                event(new \App\Events\Auth\PasswordReset($user));

                return $this->response->noContent();
            default:
                return $this->response->error('could_not_reset_password', 500);
        }
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            JWTAuth::invalidate($token);

            $this->auth->setUser(null);

            return $this->response->noContent();
        } catch (TokenBlacklistedException $e) {
            throw new UnauthorizedHttpException(null, $e->getMessage());
        }
    }

    public function me()
    {
        $user = $this->auth->user();

        return $this->response->item($user, new CurrentUserTransformer);
    }
}
