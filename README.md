# Statamic Forward Authentication

Use forward authentication to login to statamic.

# Installation

From a standard Statamic V3 site, you can run:
`composer require daynnnnn/statamic-forward-auth`

# Setup

First you'll need to adjust your config/auth.php to use the forward driver on the user provider, then you'll need to add some extra attributes:

`type` - A string/class containing authentication type to use in forward driver. Supported types are listed below.

`data` - An array containing attributes to be added to users created via forward authentication.

`config` - An array containing type specific values.

# Types

## LDAP

### Config

`host`: `(string)` LDAP Host

`ssl`: `(bool)` Whether host should be accessed with SSL

`base_dn`: `(string)` Root search DN to find user in.

`username`: `(string)` Bind users username.

`password`: `(string)` Bind users password.

### Description

An ldap search will be made of the `base_dn` to find the user. If the user is found, try to bind to the found user using provided password.

## HTTP Authentication

### Config

`endpoint`: `(string)` Address on which login will be attempted

### Description

A POST request will be sent to the endpoint with the attributes of `email` and `password`. The expected response is JSON, with a `result` item containing true or false, and a `data.name` item containing a name for the user.

# Extending

You can also extend this to add your own form of forward authentication, you'll need to create a new class which implements the `AuthServiceContract` interface, and then specify your class as the `type` like this:

`'type' => App\AuthServices\MyCustomAuthService::class,`