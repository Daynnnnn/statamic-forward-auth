# Statamic Forward Authentication

Use forward authentication to login to statamic.

# How it works

<img width="626" alt="Screenshot 2021-07-31 at 16 15 20" src="https://user-images.githubusercontent.com/25618897/127768699-676427d7-2985-4dab-aaac-22e3fa498b8a.png">

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

`result`: `(array)` Where the success status and full name of the user can be found in the JSON response, example:

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

### Description

A POST request will be sent to the endpoint with the attributes of `email` and `password`. The expected response is JSON, and should contain the success status of the credentials, and if the success status is true, the full name of a user. 

# Extending

You can also extend this to add your own form of forward authentication, you'll need to create a new class which implements the `AuthServiceContract` interface, and then specify your class as the `type` like this:

`'type' => App\AuthServices\MyCustomAuthService::class,`
