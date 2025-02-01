<?php

use Daynnnnn\Statamic\Auth\ForwardAuth\ForwardAuthUserProvider;
use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\AuthServiceContract;
use Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices\HttpAuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Statamic\Auth\File\UserGroupRepository as FileUserGroupRepository;
use Statamic\Facades\User;
use Statamic\Contracts\Auth\UserGroupRepository;
use Statamic\Contracts\Auth\UserRepository;
use Statamic\Stache\Stache;
use Statamic\Stache\Stores\UsersStore;
use Statamic\Stache\Repositories\UserRepository as StacheUserRepository;

test('creates user if not found locally and is valid against forward authentication', function () {
    $forwardAuthUser = setupHttpAuthService(true);

    $userProvider = new ForwardAuthUserProvider;

    $credentials = [
        'email' => 'test+1@gmail.com',
        'password' => 'supersecure',
    ];

    $user = $userProvider->retrieveByCredentials($credentials);

    $this->assertNotNull(User::findByEmail('test+1@gmail.com'));
});

test('does not create user if not found locally and is not valid against forward authentication', function () {
    $forwardAuthUser = setupHttpAuthService(false);

    $userProvider = new ForwardAuthUserProvider;

    $credentials = [
        'email' => 'test+2@gmail.com',
        'password' => 'supersecure',
    ];

    $user = $userProvider->retrieveByCredentials($credentials);

    $this->assertNull(User::findByEmail('test+2@gmail.com'));
});

test('does not login if local password is invalid and forward authentication password is invalid', function () {
    $user = User::make()
        ->email('test+3@gmail.com')
        ->password('supersecure')
        ->data([
            'forward_auth' => true,
        ])
        ->save();

    $forwardAuthUser = setupHttpAuthService(false);

    $userProvider = new ForwardAuthUserProvider;

    $credentials = [
        'email' => 'test+3@gmail.com',
        'password' => 'notsupersecure',
    ];

    $this->assertFalse($userProvider->validateCredentials($user, $credentials));
});

test('login if local password is invalid and forward authentication password is valid', function () {
    $user = User::make()
        ->email('test+4@gmail.com')
        ->password('notsupersecure')
        ->data([
            'forward_auth' => true,
        ])
        ->save();

    $forwardAuthUser = setupHttpAuthService(true);

    $userProvider = new ForwardAuthUserProvider;

    $credentials = [
        'email' => 'test+4@gmail.com',
        'password' => 'supersecure',
    ];

    // Assert login passes
    $this->assertTrue($userProvider->validateCredentials($user, $credentials));

    // Assert passed login will update local password
    $this->assertTrue(Hash::check($credentials['password'], User::findByEmail('test+4@gmail.com')->getAuthPassword()));
});

test('login if local password is valid and forward authentication password is valid', function () {
    $user = User::make()
        ->email('test+5@gmail.com')
        ->password('supersecure')
        ->data([
            'forward_auth' => true,
        ])
        ->save();

    $forwardAuthUser = setupHttpAuthService(true);

    $userProvider = new ForwardAuthUserProvider;

    $credentials = [
        'email' => 'test+5@gmail.com',
        'password' => 'supersecure',
    ];

    // Assert login passes
    $this->assertTrue($userProvider->validateCredentials($user, $credentials));
});

test('if user is created via forward authentication, does require check against forward auth', function () {
    $user = User::make()
        ->email('test+5@gmail.com')
        ->password('supersecure')
        ->data([
            'forward_auth' => true,
        ])
        ->save();

    $forwardAuthUser = setupHttpAuthService(false);

    $userProvider = new ForwardAuthUserProvider;

    $credentials = [
        'email' => 'test+5@gmail.com',
        'password' => 'supersecure',
    ];

    // Assert login fails
    $this->assertFalse($userProvider->validateCredentials($user, $credentials));
});

test('if user isn\'t created via forward authentication, don\'t require check against forward auth', function () {
    $user = User::make()
        ->email('test+5@gmail.com')
        ->password('supersecure')
        ->data([
            'forward_auth' => false,
        ])
        ->save();

    $forwardAuthUser = setupHttpAuthService(false);

    $userProvider = new ForwardAuthUserProvider;

    $credentials = [
        'email' => 'test+5@gmail.com',
        'password' => 'supersecure',
    ];

    // Assert login passes
    $this->assertTrue($userProvider->validateCredentials($user, $credentials));
});
