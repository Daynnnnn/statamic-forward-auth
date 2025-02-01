<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth;

use Daynnnnn\Statamic\Auth\ForwardAuth\Facades\AuthService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;
use Illuminate\Support\Facades\Hash;
use Statamic\Auth\UserProvider;
use Statamic\Facades\User;

class ForwardAuthUserProvider extends UserProvider implements UserProviderContract
{
    public function retrieveByCredentials(array $credentials)
    {
        if (($user = User::findByEmail($credentials['email'])) === null) {
            AuthService::checkCredentialsAgainstForwardAuth($credentials);

            if (AuthService::credentialsValidAgainstForwardAuth()) {
                return User::make()
                    ->email($credentials['email'])
                    ->password($credentials['password'])
                    ->data(AuthService::userData())
                    ->save();
            }
        }

        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $localCredentialsValid = Hash::check($credentials['password'], $user->getAuthPassword());

        if ($user->forward_auth === true) {
            AuthService::checkCredentialsAgainstForwardAuth($credentials);

            if (! AuthService::credentialsValidAgainstForwardAuth()) {
                $localCredentialsValid = false;
            } elseif (! $localCredentialsValid) {
                $user->password($credentials['password'])->save();
                $localCredentialsValid = true;
            }
        }

        return $localCredentialsValid;
    }
}
