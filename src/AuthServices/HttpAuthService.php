<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class HttpAuthService implements AuthServiceContract
{
    protected $config;
    protected $forwardAuthUser = false;

    public function __construct() {
        $this->config = config('auth.providers.users');
    }

    public function checkCredentialsAgainstForwardAuth(array $credentials) {
        return $this->forwardAuthUser = Http::post($this->config['config']['address'], $credentials)->json();
    }

    public function credentialsValidAgainstForwardAuth() {
        return Arr::get($this->forwardAuthUser, $this->config['config']['response']['success']);
    }

    public function userData() {
        return array_merge($this->config['data'], [
            'name' => Arr::get($this->forwardAuthUser, $this->config['config']['response']['name']),
            'forward_auth' => true,
        ]);
    }
}