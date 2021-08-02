<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth;

use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\AuthServiceContract;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;
use Statamic\Auth\UserProvider;
use Statamic\Facades\User;

class ForwardAuthUserProvider extends UserProvider implements UserProviderContract
{
    protected $authService;

    public function __construct(AuthServiceContract $authService) {
        $this->authService = $authService;
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (($user = User::findByEmail($credentials['email'])) === null) {
            $this->authService->checkCredentialsAgainstForwardAuth($credentials);

            if ($this->authService->credentialsValidAgainstForwardAuth()) {
                return User::make()
                        ->email($credentials['email'])
                        ->password($credentials['password'])
                        ->data($this->authService->userData())
                        ->save();
            }
        }

        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $localCredentialsValid = Hash::check($credentials['password'], $user->getAuthPassword());

        if ($user->forward_auth === true) {
            $this->authService->checkCredentialsAgainstForwardAuth($credentials);

            if (!$this->authService->credentialsValidAgainstForwardAuth()) {
                $localCredentialsValid = false;
            } elseif (!$localCredentialsValid) {
                $user->password($credentials['password'])->save();
                $localCredentialsValid = true;
            }
        }

        return $localCredentialsValid;
    }
}
