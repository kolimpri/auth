<?php

namespace Kolimpri\Auth\Http\Controllers\Settings;

use Exception;
use Kolimpri\Auth\kAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kolimpri\Auth\Http\Controllers\Controller;
use Kolimpri\Auth\Events\Team\Created as TeamCreated;
use Kolimpri\Auth\Events\Team\Deleting as DeletingTeam;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Kolimpri\Auth\Contracts\Repositories\TeamRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class TeamController extends Controller
{
    use ValidatesRequests;

    /**
     * The team repository instance.
     *
     * @var \Kolimpri\Auth\Contracts\Repositories\TeamRepository
     */
    protected $teams;

    /**
     * Create a new controller instance.
     *
     * @param  \Kolimpri\Auth\Contracts\Repositories\TeamRepository  $teams
     * @return void
     */
    public function __construct(TeamRepository $teams)
    {
        $this->teams = $teams;

        $this->middleware('auth');
    }

    /**
     * Create a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (kAuth::$validateNewTeamsWith) {
            $this->callCustomValidator(
                kAuth::$validateNewTeamsWith, $request
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);
        }

        $team = $this->teams->create(
            $user, ['name' => $request->name]
        );

        event(new TeamCreated($team));

        return $this->teams->getAllTeamsForUser($user);
    }

    /**
     * Show the edit screen for a given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()->findOrFail($teamId);

        $activeTab = $request->get(
            'tab', kAuth::firstTeamSettingsTabKey($team, $user)
        );

        return view('auth::settings.team', compact('team', 'activeTab'));
    }

    /**
     * Update the team's owner information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)
                ->findOrFail($teamId);

        $this->validateTeamUpdate($request, $team);

        if (kAuth::$updateTeamsWith) {
            $this->callCustomUpdater(kAuth::$updateTeamsWith, $request, [$team]);
        } else {
            $team->fill(['name' => $request->name])->save();
        }

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Validate a team update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Kolimpri\Auth\Teams\Team
     * @return void
     */
    protected function validateTeamUpdate(Request $request, $team)
    {
        if (kAuth::$validateTeamUpdatesWith) {
            $this->callCustomValidator(
                kAuth::$validateTeamUpdatesWith, $request, [$team]
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);
        }
    }

    /**
     * Switch the team the user is currently viewing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function switchCurrentTeam(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()->findOrFail($teamId);

        $user->switchToTeam($team);

        return back();
    }

    /**
     * Update a team member on the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function updateTeamMember(Request $request, $teamId, $userId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)->findOrFail($teamId);

        $userToUpdate = $team->users->find($userId);

        if (! $userToUpdate) {
            abort(404);
        }

        $this->validateTeamMemberUpdate($request, $team, $userToUpdate);

        if (kAuth::$updateTeamMembersWith) {
            $this->callCustomUpdater(kAuth::$updateTeamMembersWith, $request, [$team, $userToUpdate]);
        } else {
            $userToUpdate->teams()->updateExistingPivot(
                $team->id, ['role' => $request->role]
            );
        }

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Validate a team update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateTeamMemberUpdate(Request $request, $team, $user)
    {
        if (kAuth::$validateTeamMemberUpdatesWith) {
            $this->callCustomValidator(
                kAuth::$validateTeamMemberUpdatesWith, $request, [$team, $user]
            );
        } else {
            $availableRoles = implode(
                ',', array_except(array_keys(kAuth::roles()), 'owner')
            );

            $this->validate($request, [
                'role' => 'required|in:'.$availableRoles,
            ]);
        }
    }

    /**
     * Remove a team member from the team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function removeTeamMember(Request $request, $teamId, $userId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)->findOrFail($teamId);

        $team->removeUserById($userId);

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Remove the user from the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function leaveTeam(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()
                    ->where('owner_id', '!=', $user->id)
                    ->where('id', $teamId)->firstOrFail();

        $team->removeUserById($user->id);

        return $this->teams->getAllTeamsForUser($user);
    }

    /**
     * Destroy the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $request->user()->teams()
                ->where('owner_id', $user->id)
                ->findOrFail($teamId);

        event(new DeletingTeam($team));

        $team->users()->where('current_team_id', $team->id)
                        ->update(['current_team_id' => null]);

        $team->users()->detach();

        $team->delete();

        return $this->teams->getAllTeamsForUser($user);
    }
}
