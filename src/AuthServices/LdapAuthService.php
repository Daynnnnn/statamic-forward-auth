<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

use LdapRecord\Connection;
use Daynnnnn\Statamic\Auth\ForwardAuth\Exceptions\LdapRecordNotFoundException;
use Illuminate\Support\Arr;

class LdapAuthService implements AuthServiceContract
{
    protected $config;
    protected $data;
    protected $forwardAuthUser = false;

    public function __construct() {
        if (!class_exists(Connection::class)) {
            throw new LdapRecordNotFoundException("Using the LDAP service requires the LdapRecord package", 1);
        }

        $service = config('statamic.forward-authentication.default');
        $this->config = config("statamic.forward-authentication.services.$service");
        $this->data = config("statamic.forward-authentication.data");
    }

    public function checkCredentialsAgainstForwardAuth(array $credentials): array {
        $connectionConfig = [
            'hosts' => $this->config['hosts'],
            'port' => $this->config['port'],
            'use_ssl' => $this->config['use_ssl'],
            'base_dn' => $this->config['base_dn'],
        ];

        // Connect as "Root" LDAP user
        $mainConnection = new Connection([
            ...$connectionConfig,
            'username' => $this->config['username'],
            'password' => $this->config['password'],
        ]);

        // Start try block, as any exceptions past here will be authentication issues,
        // end block with finally for the same reason.
        try {
            // Search for user with user provided email
            $user = $mainConnection->query()->where('mail', '=', $credentials['email'])->first();

            // Try connect to the LDAP provider with found users DN and provided password
            $mainConnection = new Connection([
                ...$connectionConfig,
                'username' => $user->distinguishedname['0'],
                'password' => $credentials['password'],
            ]);

            // Connection will throw an exception if it fails, so if we get to 
            // this step then set the forwardAuthUser to the returned user
            $this->forwardAuthUser = $user;
        } finally {
            return $this->forwardAuthUser;
        }
    }

    public function credentialsValidAgainstForwardAuth(): bool {
        return $this->forwardAuthUser !== false;
    }

    public function userData(): array {
        return array_merge($this->data, [
            'name' => $this->forwardAuthUser->cn[0],
            'forward_auth' => true,
        ]);
    }
}
