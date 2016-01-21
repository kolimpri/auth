<?php

namespace Kolimpri\Auth\Http\Controllers\Settings;

use Kolimpri\Auth\kAuth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Kolimpri\Auth\Repositories\UserRepository;

class DashboardController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var \Kolimpri\Auth\Repositories\UserRepository
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  \Kolimpri\Auth\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;

        $this->middleware('auth');
    }

    /**
     * Show the settings dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = [
            'activeTab' => $request->get('tab', kAuth::firstSettingsTabKey()),
            'invoices' => [],
            'user' => $this->users->getCurrentUser(),
        ];

        return view('auth::settings.dashboard', $data);
    }
}
