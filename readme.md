# Auth Module

- [Introduction](#introduction)
- [Installation](#installation)
- [Teams](#teams)
- [Customizing Auth Views](#customizing-auth-views)
- [Customizing Auth JavaScript](#customizing-auth-javascript)

<a name="introduction"></a>
## Introduction

Kolimpri Auth is an experimental project based on Laravel Spark with the purpose of provide an auth module for Laravel 5.1 applications.

<a name="installation"></a>
## Installation (as with Laravel Spark)

First, install the Auth installer and make sure that the global Composer `bin` directory is within your system's `$PATH`:
```
	composer global require "kolimpri/auth-installer=~1.0"
```
Next, create a new Laravel application and install Auth:
```
	laravel new application

	cd application

	auth install
```
After installing Auth, be sure to migrate your database, install the NPM dependencies, and run the `gulp` command.

You may also wish to review the `AuthServiceProvider` class that was installed in your application. This provider is the central location for customizing your Auth installation.

<a name="teams"></a>
## Teams

To enable teams, simply use the `CanJoinTeams` trait on your `User` model. The trait has already been imported in the top of the file, so you only need to add it to the model itself:
```php
	class User extends Model implements TwoFactorAuthenticatableContract,
	                                    BillableContract,
	                                    CanResetPasswordContract
	{
	    use Billable, CanJoinTeams, CanResetPassword, TwoFactorAuthenticatable;
	}
```
Once teams are enabled, a team name will be required during registration, and a `Teams` tab will be available in the user settings dashboard.

### Roles

Team roles may be defined in the `customizeRoles` method of the `AuthServiceProvider`.

<a name="customizing-auth-views"></a>
## Customizing Auth Views

You may publish Auth's common Blade views by using the `vendor:publish` command:

```
	php artisan vendor:publish --tag=auth-basics
```

All published views will be placed in `resources/views/vendor/auth`.

If you would like to publish every Auth view, you may use the `auth-full` tag:

```
	php artisan vendor:publish --tag=auth-full
```

<a name="customizing-auth-javascript"></a>
## Customizing Auth JavaScript

The `resources/assets/js/Auth/components.js` file contains the statements to load some common Auth Vue components. [Vue](http://vuejs.org) is the JavaScript framework used by the Auth registration and settings screens.

You are free to change any of these require statements to load your own Vue component for a given screen. Most likely, you will want to copy the original component as a starting point for your customization.

## Credits

Laravel Spark, Taylor Otwell - Original Code
