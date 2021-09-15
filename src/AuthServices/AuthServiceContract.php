<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

interface AuthServiceContract
{
    public function checkCredentialsAgainstForwardAuth(array $credentials): array;

    public function credentialsValidAgainstForwardAuth(): bool;
    
    public function userData(): array;
}