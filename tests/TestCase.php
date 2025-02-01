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
use Statamic\Facades\UserGroup;

class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            \Statamic\Providers\StatamicServiceProvider::class,
            \Daynnnnn\Statamic\Auth\ForwardAuth\ForwardAuthServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['statamic.users.repository' => 'file']);
    }
}