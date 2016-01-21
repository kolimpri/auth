<?php

// Terms Routes...
$router->get('terms', 'TermsController@show');

// Settings Dashboard Routes...
$router->get('settings', 'Settings\DashboardController@show');

// Profile Routes...
$router->put('settings/user', 'Settings\ProfileController@updateUserProfile');

// Team Routes...
if (kAuth::usingTeams()) {
    $router->post('settings/teams', 'Settings\TeamController@store');
    $router->get('settings/teams/{id}', 'Settings\TeamController@edit');
    $router->put('settings/teams/{id}', 'Settings\TeamController@update');
    $router->delete('settings/teams/{id}', 'Settings\TeamController@destroy');
    $router->get('settings/teams/switch/{id}', 'Settings\TeamController@switchCurrentTeam');

    $router->post('settings/teams/{id}/invitations', 'Settings\InvitationController@sendTeamInvitation');
    $router->post('settings/teams/invitations/{invite}/accept', 'Settings\InvitationController@acceptTeamInvitation');
    $router->delete('settings/teams/invitations/{invite}', 'Settings\InvitationController@destroyTeamInvitationForUser');
    $router->delete('settings/teams/{team}/invitations/{invite}', 'Settings\InvitationController@destroyTeamInvitationForOwner');

    $router->put('settings/teams/{team}/members/{user}', 'Settings\TeamController@updateTeamMember');
    $router->delete('settings/teams/{team}/members/{user}', 'Settings\TeamController@removeTeamMember');
    $router->delete('settings/teams/{team}/membership', 'Settings\TeamController@leaveTeam');
}

// Security Routes...
$router->put('settings/user/password', 'Settings\SecurityController@updatePassword');

// Authentication Routes...
$router->get('login', 'Auth\AuthController@getLogin');
$router->post('login', 'Auth\AuthController@postLogin');
$router->get('logout', 'Auth\AuthController@getLogout');

// Registration Routes...
$router->get('register', 'Auth\AuthController@getRegister');
$router->post('register', 'Auth\AuthController@postRegister');

// Password Routes...
$router->get('password/email', 'Auth\PasswordController@getEmail');
$router->post('password/email', 'Auth\PasswordController@postEmail');
$router->get('password/reset/{token}', 'Auth\PasswordController@getReset');
$router->post('password/reset', 'Auth\PasswordController@postReset');

// User API Routes...
$router->get('auth/api/users/me', 'API\UserController@getCurrentUser');

// Team API Routes...
if (kAuth::usingTeams()) {
    $router->get('auth/api/teams/invitations', 'API\InvitationController@getPendingInvitationsForUser');
    $router->get('auth/api/teams/roles', 'API\TeamController@getTeamRoles');
    $router->get('auth/api/teams/{id}', 'API\TeamController@getTeam');
    $router->get('auth/api/teams', 'API\TeamController@getAllTeamsForUser');
    $router->get('auth/api/teams/invitation/{code}', 'API\InvitationController@getInvitation');
}
