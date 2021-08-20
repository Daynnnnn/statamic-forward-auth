# Statamic Forward Authentication

Use forward authentication to login to statamic.

# How it works

<img width="626" alt="Screenshot 2021-07-31 at 16 15 20" src="https://user-images.githubusercontent.com/25618897/127768699-676427d7-2985-4dab-aaac-22e3fa498b8a.png">

# Installation

From a standard Statamic V3 site, you can run:
`composer require daynnnnn/statamic-forward-auth`

Then publish the config:
`php please vendor:publish --tag="statamic-forward-authentication"`

# Setup

First you'll need to adjust your config/auth.php to use the forward driver on the user provider:

```
'users' => [
    'driver' => 'forward',
],
```

Then you can edit `config/statamic/forward-authentication.php` to setup authentication

# Types

By default, there's 2 supported services: `http` and `ldap`

These can be selected via the `default` value in `config/statamic/forward-authentication.php`

## LDAP

### Description

An ldap search will be made of the `base_dn` to find the user. If the user is found, try to bind to the found user using provided password.

### Config

`host: (array)` List of LDAP hsots

`use_ssl: (bool)` Whether host should be accessed with SSL

`base_dn: (string)` Root search DN to find user in.

`username: (string)` Bind users DN.

`password: (string)` Bind users password.

# Requirements

The LDAP auth service requires Adldap2:

`composer require adldap2/adldap2`

## HTTP Authentication

### Description

A POST request will be sent to the endpoint with the attributes of `email` and `password`. The expected response is JSON, and should contain the success status of the credentials, and if the success status is true, the full name of a user. 

### Config

`endpoint: (string)` Address on which login will be attempted

`result: (array)` Where the success status and full name of the user can be found in the JSON response, example:

If your JSON response looks like this:

```
{
  "result": true,
  "data": {
    "name": "Daniel Pegg"
  }
}
```

Your result array should look like this:

```
result => [
    'success' => 'result',
    'name' => 'data.name',
],
```

# Extending

You can also extend this to add your own form of forward authentication, you'll need to create a new class which implements the `AuthServiceContract` interface, and then set a service in `config/statamic/forward-authentication.php`. With the service, the only requirment is that the driver is defined as your class, like this:

`'driver' => App\AuthServices\MyCustomAuthService::class,`

The rest of the config can be setup based on what your custom authentication service needs.
