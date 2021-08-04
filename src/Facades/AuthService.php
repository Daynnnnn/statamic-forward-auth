<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\Facades;

use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\AuthServiceContract;
use Illuminate\Support\Facades\Facade;

class AuthService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AuthServiceContract::class;
    }
}
