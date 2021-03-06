<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Type
    |--------------------------------------------------------------------------
    |
    | Which forward authentication service should be used.
    |
    | Supported: "http", "ldap", MyCustomAuthService::class
    |
    */

    'default' => env('STATAMIC_FORWARD_AUTH_SERVICE', 'http'),

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    |
    | An array of configurations availible to each authentication service
    | respectively.
    |
    */

    'services' => [

        'http' => [

            'driver' => 'http',

            'address' => env('STATAMIC_FORWARD_AUTH_HTTP_ADDRESS'),
    
            'response' => [
                'success' => 'result',
                'name' => 'data.name',
            ],
        ],

        'ldap' => [

            'driver' => 'ldap',

            'hosts' => [
                env('STATAMIC_FORWARD_AUTH_LDAP_HOST'),
            ],
            
            'use_ssl' => env('STATAMIC_FORWARD_AUTH_LDAP_SSL', false),
    
            'base_dn' => env('STATAMIC_FORWARD_AUTH_BASE_DN'),
    
            'username' => env('STATAMIC_FORWARD_AUTH_BIND_USERNAME'),
    
            'password' => env('STATAMIC_FORWARD_AUTH_BIND_PASSWORD'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    |
    | An array of additional atributes which are added to users created via
    | forward authentication.
    |
    */

    'data' => [
        'super' => true,
    ],

];
