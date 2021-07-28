<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

interface AuthServiceContract
{
    public function checkCredentialsAgainstForwardAuth(array $credentials);

    public function credentialsValidAgainstForwardAuth();
    
    public function userData();
}