
/*
 |--------------------------------------------------------------------------
 | Laravel Auth - Creating Amazing Experiences.
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Auth which are
 | libraries such as Vue and jQuery. Then, we will load the components
 | which manage the Auth screens such as the user settings screens.
 |
 | Next, we will create the root Vue application for Auth. We'll only do
 | this if a "auth-app" ID exists on the page. Otherwise, we will not
 | attempt to create this Vue application so we can avoid conflicts.
 |
 */

require('kolimpri-auth/core/dependencies');

if ($('#auth-app').length > 0) {
	require('./auth/components')

	new Vue(require('kolimpri-auth'));
}
