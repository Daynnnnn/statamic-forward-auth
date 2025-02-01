<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

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
