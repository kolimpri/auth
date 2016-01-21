<?php

namespace Kolimpri\Auth\Http\Controllers\Auth;

use App\User;
use Validator;
use Exception;
use Carbon\Carbon;
use Kolimpri\Auth\kAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kolimpri\Auth\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Kolimpri\Auth\Events\User\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Kolimpri\Auth\Events\Team\Created as TeamCreated;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Kolimpri\Auth\Contracts\Repositories\UserRepository;
use Kolimpri\Auth\Contracts\Repositories\TeamRepository;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends BaseController
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins, ValidatesRequests;

    /**
     * The user repository instance.
     *
     * @var \Kolimpri\Auth\Contracts\Repositories\UserRepository
     */
    protected $users;

    /**
     * The team repository instance.
     *
     * @var \Kolimpri\Auth\Contracts\Repositories\TeamRepository
     */
    protected $teams;

    /**
     * The URI for the login route.
     *
     * @var string
     */
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Kolimpri\Auth\Contracts\Repositories\UserRepository  $users
     * @param  \Kolimpri\Auth\Contracts\Repositories\TeamRepository  $teams
     * @return void
     */
    public function __construct(UserRepository $users, TeamRepository $teams)
    {
        $this->users = $users;
        $this->teams = $teams;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('auth::auth.authenticate');
    }

    /**
     * Send the post-authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request)
    {
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Show the application registration form.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getRegister(Request $request)
    {
        return view('auth::auth.registration.simple');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        return $this->register($request);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function register(Request $request)
    {
        $this->validateRegistration($request);

        $user = $this->users->createUserFromRegistrationRequest(
            $request
        );

        if ($request->team_name) {
            $team = $this->teams->create($user, ['name' => $request->team_name]);

            event(new TeamCreated($team));
        }

        if ($request->invitation) {
            $this->teams->attachUserToTeamByInvitation($request->invitation, $user);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Validate the new registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateRegistration(Request $request)
    {
        if (kAuth::$validateRegistrationsWith) {
            $this->callCustomRegistrationValidator($request);
        } else {
            $this->validateDefaultRegistration($request);
        }
    }

    /**
     * Validate the new custom registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function callCustomRegistrationValidator(Request $request)
    {
        $validator = $this->getCustomValidator(
            kAuth::$validateRegistrationsWith, $request
        );

        $this->callCustomValidator($validator, $request);
    }

    /**
     * Validate a new registration using the default rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateDefaultRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'terms' => 'accepted',
        ]);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getLogout(Request $request)
    {
        $request->session()->flush();

        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return kAuth::$afterAuthRedirectTo;
    }
}
