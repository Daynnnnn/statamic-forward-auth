<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class HttpAuthService implements AuthServiceContract
{
    protected $config;
    protected $data;
    protected $forwardAuthUser = false;

    public function __construct() {
        $this->config = config("forward-authentication.services.http");
        $this->data = config("forward-authentication.data");
    }

    public function checkCredentialsAgainstForwardAuth(array $credentials) {
        return $this->forwardAuthUser = Http::post($this->config['address'], $credentials)->json();
    }

    public function credentialsValidAgainstForwardAuth() {
        return Arr::get($this->forwardAuthUser, $this->config['response']['success']);
    }

    public function userData() {
        return array_merge($this->data, [
            'name' => Arr::get($this->forwardAuthUser, $this->config['response']['name']),
            'forward_auth' => true,
        ]);
    }
}