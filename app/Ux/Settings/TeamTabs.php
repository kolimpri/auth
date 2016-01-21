<?php

namespace Kolimpri\Auth\Ux\Settings;

class TeamTabs extends Tabs
{
    /**
     * Get the tab configuration for the "Owner Settings" tab.
     *
     * @return \Kolimpri\Auth\Ux\Settings\Tab
     */
    public function owner()
    {
        return new Tab('Owner Settings', 'auth::settings.team.tabs.owner', 'fa-star', function ($team, $user) {
            return $user->ownsTeam($team);
        });
    }

    /**
     * Get the tab configuration for the "Membership" tab.
     *
     * @return \Kolimpri\Auth\Ux\Settings\Tab
     */
    public function membership()
    {
        return new Tab('Membership', 'auth::settings.team.tabs.membership', 'fa-users');
    }
}
