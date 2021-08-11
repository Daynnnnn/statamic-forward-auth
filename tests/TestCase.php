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
    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge(require $path, $config));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $time = time();

        config(['filesystems.disks.standard' => [
            'driver' => 'local',
            'root' => storage_path('app/'.$time),
        ]]);

        app()->alias(Stache::class, 'stache');
        app()['stache'] = app()['stache']->getFacadeRoot()->registerStore((new UsersStore)->directory(storage_path('app/'.$time.'/users')));
    
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