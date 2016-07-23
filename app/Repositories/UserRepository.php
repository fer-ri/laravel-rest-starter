<?php

namespace App\Repositories;

use Laravel\Socialite\Contracts\User as ProviderUser;

class UserRepository extends AbstractRepository
{
    public function model()
    {
        return \App\Models\User::class;
    }

    public function findByEmail($email)
    {
        return $this->model
            ->where('email', $email)
            ->firstOrFail();
    }

    public function findForActivate($code)
    {
        return $this->model
            ->where('activation_code', $code)
            ->whereNull('activated_at')
            ->first();
    }

    public function findOrCreateSocialAccount(ProviderUser $providerUser, $driver)
    {
        $account = \App\Models\SocialAccount::whereProvider($driver)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {
            $account = new SocialAccount([
                'provider' => $driver,
                'provider_user_id' => $providerUser->getId(),
            ]);

            $user = $this->model->whereEmail($providerUser->getEmail())->first();

            if (! $user) {
                $user = $this->model->create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'activated_at' => new \DateTime,
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }
}
