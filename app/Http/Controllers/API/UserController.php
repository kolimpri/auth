<?php

namespace Kolimpri\Auth\Http\Controllers\API;

use Kolimpri\Auth\kAuth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kolimpri\Auth\Contracts\Repositories\UserRepository;

class UserController extends Controller
{
	/**
	 * The user repository instance.
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
	 * Get the current user of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCurrentUser()
	{
		return $this->users->getCurrentUser();
	}
}
