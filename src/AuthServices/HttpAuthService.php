<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class HttpAuthService implements AuthServiceContract
{
    protected $config;

    protected $data;

    protected $forwardAuthUser = false;

    public function __construct()
    {
        $service = config('statamic.forward-authentication.default');
        $this->config = config("statamic.forward-authentication.services.$service");
        $this->data = config('statamic.forward-authentication.data');
    }

    public function checkCredentialsAgainstForwardAuth(array $credentials): array|false
    {
        return $this->forwardAuthUser = Http::post($this->config['address'], $credentials)->json();
    }

    public function credentialsValidAgainstForwardAuth(): bool
    {
        return Arr::get($this->forwardAuthUser, $this->config['response']['success']);
    }

    public function userData(): array
    {
        return array_merge($this->data, [
            'name' => Arr::get($this->forwardAuthUser, $this->config['response']['name']),
            'forward_auth' => true,
        ]);
    }
}
