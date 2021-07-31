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

            return null;
        }

        if ($user->forward_auth === true) {
            $this->authService->checkCredentialsAgainstForwardAuth($credentials);

            if (!$this->authService->credentialsValidAgainstForwardAuth()) {
                return null;
            } elseif (!$this->validateCredentials($user, $credentials)) {
                $user->password($credentials['password'])->save();
            }
        }

        return $user;
    }
}
