<?php

namespace App\Helpers;

use Socialite;
use App\User;
use Adaojunior\Passport\SocialUserResolverInterface;
use Adaojunior\Passport\SocialGrantException;

class SocialUserResolver implements SocialUserResolverInterface {
    public function resolve($network, $accessToken, $accessTokenSecret = null)
    {
        switch ($network) {
            case 'facebook':
                return $this->authWithFacebook($accessToken);
                break;

            case 'google':
                return $this->authWithGoogle($accessToken);
                break;

            default:
                throw SocialGrantException::invalidNetwork();
                break;
        }
    }

    protected function authWithFacebook($accessToken)
    {
        $user = Socialite::driver('facebook')->userFromToken($accessToken);
        return $this->findOrCreate($user);
    }

    protected function authWithGoogle($accessToken)
    {
        $user = Socialite::driver('google')->userFromToken($accessToken);
        return $this->findOrCreate($user);
    }


    protected function findOrCreate($user)
    {
        return User::query()->firstOrCreate(['email' => $user->email], [
            'email'     => $user->email,
            'name'      => $user->name,
            'password'  => bcrypt(str_random(64)),
            'avatar'    => $user->avatar,
        ]);
    }
}
  