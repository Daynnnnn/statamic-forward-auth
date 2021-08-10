<?php

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

    $forwardAuthUser = setupHttpAuthService(true);

    $this->assertEquals($mockRepsonse, $forwardAuthUser->checkCredentialsAgainstForwardAuth($credentials));
});

test('credentialsValidAgainstForwardAuth returns true with valid user', function() {
    $credentials = [
        'email' => 'test@gmail.com',
        'password' => 'supersecure',
    ];

    $forwardAuthUser = tap(setupHttpAuthService(true))->checkCredentialsAgainstForwardAuth($credentials);

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

    $forwardAuthUser = tap(setupHttpAuthService(true))->checkCredentialsAgainstForwardAuth($credentials);

    $this->assertEquals($expectedData, $forwardAuthUser->userData());
});