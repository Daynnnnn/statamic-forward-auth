<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

use Adldap\Adldap;
use Daynnnnn\Statamic\Auth\ForwardAuth\Exceptions\AdldapNotFoundException;
use Illuminate\Support\Arr;

class LdapAuthService implements AuthServiceContract
{
    protected $config;
    protected $data;
    protected $forwardAuthUser = false;

    public function __construct() {
        if (!class_exists(Adldap::class)) {
            throw new AdldapNotFoundException("Using the LDAP service requires the Adldap2 package", 1);
        }

        $service = config('statamic.forward-authentication.default');
        $this->config = config("statamic.forward-authentication.services.$service");
        $this->data = config("statamic.forward-authentication.data");
    }

    public function checkCredentialsAgainstForwardAuth(array $credentials): array {
        // Create LDAP client
        $ad = new Adldap();

        // Add ldap provider based on config
        $ad->addProvider(Arr::only($this->config, [
            'hosts',
            'use_ssl',
            'base_dn',
            'username',
            'password',
        ]));

        // Connect to LDAP provider using bind user
        $provider = $ad->connect('default');

        // Start try block, as any exceptions past here will be authentication issues,
        // end block with finally for the same reason.
        try {
            // Search for user with user provided email
            $user = $provider->search()->where('mail', '=', $credentials['email'])->first();
            
            // Try connect to the LDAP provider with found users DN and provided password
            $provider = $ad->connect(
                'default',
                $user->distinguishedname['0'],
                $credentials['password'],
            );

            // Connection will throw an exception if it fails, so if we get to 
            // this step then set the forwardAuthUser to the returned user
            $this->forwardAuthUser = $user;
        } finally {
            return $this->forwardAuthUser;
        }
    }

    public function credentialsValidAgainstForwardAuth(): bool {
        return (bool)$this->forwardAuthUser;
    }

    public function userData(): array {
        return array_merge($this->data, [
            'name' => $this->forwardAuthUser->cn[0],
            'forward_auth' => true,
        ]);
    }
}