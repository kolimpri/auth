<?php

namespace Kolimpri\Auth\Repositories;

use DB;
use Carbon\Carbon;
use Kolimpri\Auth\kAuth;
use Illuminate\Http\Request;
use Kolimpri\Auth\InteractsWithkAuthHooks;
use Kolimpri\Auth\Contracts\Repositories\UserRepository as Contract;

class UserRepository implements Contract
{
    use InteractsWithkAuthHooks;

    /**
     * Get the current user of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrentUser()
    {
        $user = kAuth::user();

        if (kAuth::usingTeams()) {
            $user->currentTeam;
        }

        return $user;
    }

    /**
     * Create a new user of the application based on a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function createUserFromRegistrationRequest(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $user = $this->createNewUser($request);

            return $user;
        });
    }

    /**
     * Create a new user of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function createNewUser(Request $request)
    {
        if (kAuth::$createUsersWith) {
            return $this->callCustomUpdater(kAuth::$createUsersWith, $request);
        } else {
            return $this->createDefaultUser($request);
        }
    }

    /**
     * Create the default user instance for a new registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function createDefaultUser(Request $request)
    {
        $model = config('auth.model');

        return (new $model)->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }
}
