<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

use Illuminate\Support\Facades\Http;


class HttpAuthService implements AuthServiceContract
{
    protected $config;
    protected $forwardAuthUser = false;

    public function __construct() {
        $this->config = config('auth.providers.users.config');
    }

    public function checkCredentialsAgainstForwardAuth(array $credentials) {
        return $this->forwardAuthUser = Http::post($this->config['address'], $credentials)->json();
    }

    public function credentialsValidAgainstForwardAuth() {
        return $this->forwardAuthUser['result'];
    }

    public function userData() {
        return array_merge($this->config['data'], [
            'name' => $this->forwardAuthUser['data']['name'],
            'forward_auth' => true,
        ]);
    }
}