/*
 * Load the Auth components.
 */
require('./core/components');

/**
 * Export the Auth application.
 */
module.exports = {
    el: '#auth-app',

    /*
     * Bootstrap the application. Load the initial data.
     */
    ready: function () {
        $(function() {
            $('.auth-first-field').filter(':visible:first').focus();
        });

        if (kAuth.userId) {
            this.getUser();
        }

        if (kAuth.currentTeamId) {
            this.getTeams();
            this.getCurrentTeam();
        }

        this.whenReady();
    },


    events: {
        /**
         * Handle requests to update the current user from a child component.
         */
        updateUser: function () {
            this.getUser();
        },


        /**
         * Handle requests to update the teams from a child component.
         */
        updateTeams: function () {
            this.getTeams();
        },


        /**
         * Receive an updated team list from a child component.
         */
        teamsUpdated: function (teams) {
            this.$broadcast('teamsRetrieved', teams);
        }
    },


    methods: {
        /**
         * This method would be overridden by developer.
         */
        whenReady: function () {
            //
        },


        /**
         * Retrieve the user from the API and broadcast it to children.
         */
        getUser: function () {
            this.$http.get('/auth/api/users/me')
                .success(function(user) {
                    this.$broadcast('userRetrieved', user);
                });
        },

        /*
         * Get all of the user's current teams from the API.
         */
        getTeams: function () {
            this.$http.get('/auth/api/teams')
                .success(function (teams) {
                    this.$broadcast('teamsRetrieved', teams);
                });
        },


        /*
         * Get the user's current team from the API.
         */
        getCurrentTeam: function () {
            this.$http.get('/auth/api/teams/' + kAuth.currentTeamId)
                .success(function (team) {
                    this.$broadcast('currentTeamRetrieved', team);
                });
        }
    }
};
