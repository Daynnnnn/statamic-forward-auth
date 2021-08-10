<?php

use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\HttpAuthService;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(\Daynnnnn\Statamic\Auth\ForwardAuth\Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function setupHttpAuthService($status) {
    $mockRepsonse = [
        'success' => $status,
        'data' => [
            'name' => 'Test Name',
        ],
    ];

    $config = [
        'driver' => 'forward',
        'type' => 'http',
        'config' => [
            'address' => 'http://localhost/login',
            'response' => [
                'success' => 'success',
                'name' => 'data.name',
            ],
        ],
        'data' => [
            'super' => true,
        ],
    ];
    
    config(['auth.providers.users' => $config]);

    Http::fake(Http::response($mockRepsonse, 200));

    return new HttpAuthService;
}