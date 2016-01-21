<?php

namespace Kolimpri\Auth\Http\Controllers\Settings;

use Exception;
use Kolimpri\Auth\kAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kolimpri\Auth\Events\User\ProfileUpdated;
use Kolimpri\Auth\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Kolimpri\Auth\Contracts\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class ProfileController extends Controller
{
    use ValidatesRequests;

    /**
     * The user repository implementation.
     *
     * @var \Kolimpri\Auth\Contracts\Repositories\UserRepository
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  \Kolimpri\Auth\Contracts\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;

        $this->middleware('auth');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserProfile(Request $request)
    {
        $this->validateUserProfile($request);

        if (kAuth::$updateProfilesWith) {
            $this->callCustomUpdater(kAuth::$updateProfilesWith, $request);
        } else {
            Auth::user()->fill($request->all())->save();
        }

        event(new ProfileUpdated(Auth::user()));

        return $this->users->getCurrentUser();
    }

    /**
     * Validate the incoming request to update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateUserProfile(Request $request)
    {
        if (kAuth::$validateProfileUpdatesWith) {
            $this->callCustomValidator(
                kAuth::$validateProfileUpdatesWith, $request
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email,'.Auth::id(),
            ]);
        }
    }
}
