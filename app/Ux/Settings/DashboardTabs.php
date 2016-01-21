<?php

namespace Kolimpri\Auth\Ux\Settings;

use Kolimpri\Auth\kAuth;

class DashboardTabs extends Tabs
{
    /**
     * Get the tab configuration for the "profile" tab.
     *
     * @return \Kolimpri\Auth\Ux\Settings\Tab
     */
    public function profile()
    {
        return new Tab('Profile', 'auth::settings.tabs.profile', 'fa-user');
    }

    /**
     * Get the tab configuration for the "teams" tab.
     *
     * @return \Kolimpri\Auth\Ux\Settings\Tab
     */
    public function teams()
    {
        return new Tab('Teams', 'auth::settings.tabs.teams', 'fa-users', function () {
            return kAuth::usingTeams();
        });
    }

    /**
     * Get the tab configuration for the "security" tab.
     *
     * @return \Kolimpri\Auth\Ux\Settings\Tab
     */
    public function security()
    {
        return new Tab('Security', 'auth::settings.tabs.security', 'fa-lock');
    }
}
