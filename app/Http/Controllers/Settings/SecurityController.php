<?php

namespace Kolimpri\Auth\Http\Controllers\Settings;

use Exception;
use Kolimpri\Auth\kAuth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kolimpri\Auth\Events\User\ProfileUpdated;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Kolimpri\Auth\Contracts\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class SecurityController extends Controller
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
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if (! Hash::check($request->old_password, Auth::user()->password)) {
            return response()->json(
                ['The old password you provided is incorrect.'], 422
            );
        }

        Auth::user()->password = Hash::make($request->password);

        Auth::user()->save();
    }
}
