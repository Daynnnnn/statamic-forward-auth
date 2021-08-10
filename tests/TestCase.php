<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\Tests;

use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\AuthServiceContract;
use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\HttpAuthService;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Statamic\Auth\File\UserGroupRepository as FileUserGroupRepository;
use Statamic\Contracts\Auth\UserGroupRepository;
use Statamic\Contracts\Auth\UserRepository;
use Statamic\Facades\Stache;
use Statamic\Stache\Stores\UsersStore;
use Statamic\Stache\Repositories\UserRepository as StacheUserRepository;


class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        app()->alias(Stache::class, 'stache');
        app()['stache'] = app()['stache']->getFacadeRoot()->registerStore((new UsersStore)->directory(storage_path('app/'.time().'/daynnnnn')));
    
        app()->singleton('stache.indexes', function () {
            return collect();
        });
    
        app()->singleton(UserRepository::class, function ($app) {
            return new StacheUserRepository($app['stache']);
        });
    
        app()->singleton(UserGroupRepository::class, function ($app) {
            return new FileUserGroupRepository;
        });
    
        app()->bind(AuthServiceContract::class, function () {
            return new HttpAuthService;
        });
    }
}