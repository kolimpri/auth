<?php

namespace Kolimpri\Auth\Teams;

use Kolimpri\Auth\kAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get all of the users that belong to the team.
     */
    public function users()
    {
        return $this->belongsToMany(
            config('auth.model'), 'user_teams', 'team_id', 'user_id'
        )->withPivot('role');
    }

    /**
     * Get the owner of the team.
     */
    public function owner()
    {
        return $this->belongsTo(config('auth.model'), 'owner_id');
    }

    /**
     * Get all of the pending invitations for the team.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class)->orderBy('created_at', 'desc');
    }

    /**
     * Invite a user to the team by e-mail address.
     *
     * @param  string  $email
     * @return \Kolimpri\Auth\Teams\Invitation
     */
    public function inviteUserByEmail($email)
    {
        $model = config('auth.model');

        $invitedUser = (new $model)->where('email', $email)->first();

        $invitation = $this->invitations()
                ->where('email', $email)->first();

        if (! $invitation) {
            $invitation = $this->invitations()->create([
                'user_id' => $invitedUser ? $invitedUser->id : null,
                'email' => $email,
                'token' => str_random(40),
            ]);
        }

        $email = $invitation->user_id
                        ? 'auth::emails.team.invitations.existing'
                        : 'auth::emails.team.invitations.new';

        Mail::send($email, compact('invitation'), function ($m) use ($invitation) {
            $m->to($invitation->email)->subject('New Invitation!');
        });

        return $invitation;
    }

    /**
     * Remove a user from the team by their ID.
     *
     * @param  int  $userId
     * @return void
     */
    public function removeUserById($userId)
    {
        $this->users()->detach([$userId]);

        $userModel = config('auth.model');

        $removedUser = (new $userModel)->find($userId);

        if ($removedUser) {
            $removedUser->refreshCurrentTeam();
        }
    }
}