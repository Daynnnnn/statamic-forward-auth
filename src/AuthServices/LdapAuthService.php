<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\AuthServices;

class LdapAuthService implements AuthServiceContract
{
    protected $config;
    protected $data;
    protected $forwardAuthUser = false;

    public function __construct() {
        $this->config = config("forward-authentication.services.ldap");
        $this->data = config("forward-authentication.data");
    }

    public function checkCredentialsAgainstForwardAuth(array $credentials) {
        $ldapServer   = $this->config['host'];
        $bindUser     = $this->config['username'];
        $bindPassword = $this->config['password'];
        $baseDn       = $this->config['base_dn'];

        $bindDN = 'uid=' . $bindUser . ',' . $baseDn;

        $ldapConnection = ldap_connect($ldapServer);

        if ($this->config['ssl']) {
            ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        }
        
        if ($ldapConnection) {
            $ldapBind = ldap_bind($ldapConnection, $bindDN, $bindPassword) or die ("Error trying to bind: ".ldap_error($ldapConnection));
            if ($ldapBind) {
                $result = ldap_search($ldapConnection, $baseDn, '(mail='.$credentials['email'].')', ['name']) or die ("Error in search query: ".ldap_error($ldapConnection));
                $result = ldap_get_entries($ldapConnection, $result)[0] ?? false;

                if ($result) {
                    try {
                        ldap_bind($ldapConnection, $result['dn'], $credentials['password']);
                    } catch (\ErrorException $e) {
                        $result = false;
                    }
                }

                $this->forwardAuthUser = $result;
            }
        }
    }

    public function credentialsValidAgainstForwardAuth() {
        return (bool)$this->forwardAuthUser;
    }

    public function userData() {
        return array_merge($this->data, [
            'name' => $this->forwardAuthUser['cn'][0],
            'forward_auth' => true,
        ]);
    }
}