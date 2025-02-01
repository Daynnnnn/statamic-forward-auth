<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth;

use Illuminate\Support\Facades\Auth;
use Statamic\Providers\AddonServiceProvider;

class ForwardAuthServiceProvider extends AddonServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/forward-authentication.php', 'statamic.forward-authentication');

        $this->app->bind(AuthServices\AuthServiceContract::class, function () {
            $service = config('statamic.forward-authentication.default');
            $class = $this->lookupType(config("statamic.forward-authentication.services.$service.driver"));

            return new $class;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/forward-authentication.php' => config_path('statamic/forward-authentication.php'),
        ], 'statamic-forward-authentication');

        Auth::provider('forward', function () {
            return new ForwardAuthUserProvider;
        });
    }

    protected function lookupType($type)
    {
        $types = [
            'http' => AuthServices\HttpAuthService::class,
            'ldap' => AuthServices\LdapAuthService::class,
        ];

        return $types[$type] ?? $type;
    }
}
