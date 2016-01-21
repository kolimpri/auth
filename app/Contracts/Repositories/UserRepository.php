<?php

namespace Kolimpri\Auth\Contracts\Repositories;

use Illuminate\Http\Request;

interface UserRepository
{
    /**
     * Get the current user of the application.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getCurrentUser();

    /**
     * Create a new user of the application based on a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function createUserFromRegistrationRequest(Request $request);
}
