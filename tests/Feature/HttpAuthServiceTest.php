<?php

use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\HttpAuthService;
use Illuminate\Support\Facades\Http;

function setupHttpAuthService() {
    $mockRepsonse = [
        'success' => true,
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

test('checkCredentialsAgainstForwardAuth returns expected array', function () {
    $mockRepsonse = [
        'success' => true,
        'data' => [
            'name' => 'Test Name',
        ],
    ];

    $credentials = [
        'email' => 'test@gmail.com',
        'password' => 'supersecure',
    ];

    $forwardAuthUser = setupHttpAuthService();

    $this->assertEquals($mockRepsonse, $forwardAuthUser->checkCredentialsAgainstForwardAuth($credentials));
});

test('credentialsValidAgainstForwardAuth returns true with valid user', function() {
    $credentials = [
        'email' => 'test@gmail.com',
        'password' => 'supersecure',
    ];

    $forwardAuthUser = tap(setupHttpAuthService())->checkCredentialsAgainstForwardAuth($credentials);

    $this->assertTrue($forwardAuthUser->credentialsValidAgainstForwardAuth());
});

test('userData returns expected data array', function() {
    $expectedData = [
        'super' => true,
        'forward_auth' => true,
        'name' => 'Test Name',
    ];

    $credentials = [
        'email' => 'test@gmail.com',
        'password' => 'supersecure',
    ];

    $forwardAuthUser = tap(setupHttpAuthService())->checkCredentialsAgainstForwardAuth($credentials);

    $this->assertEquals($expectedData, $forwardAuthUser->userData());
});