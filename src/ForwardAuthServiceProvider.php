<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth;

use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;
use Illuminate\Support\Facades\Auth;
use Statamic\Providers\AddonServiceProvider;

class ForwardAuthServiceProvider extends AddonServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthServices\AuthServiceContract::class, function () {
            $class = $this->lookupType(config('auth.providers.users.type'));
            return new $class;
        });
    }

    public function boot()
    {
        Auth::provider('forward', function () {
            return new ForwardAuthUserProvider;
        });
    }

    protected function lookupType($type) {
        $types = [
            'http' => AuthServices\HttpAuthService::class,
            'ldap' => AuthServices\LdapAuthService::class,
        ];

        return $types[$type] ?? $type;
    }
}
