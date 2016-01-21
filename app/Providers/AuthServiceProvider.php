<?php

namespace Kolimpri\Auth\Providers;

use Kolimpri\Auth\Console\Install;
use Illuminate\Support\ServiceProvider;
use Kolimpri\Auth\Repositories\UserRepository;
use Kolimpri\Auth\Repositories\TeamRepository;
use Kolimpri\Auth\Contracts\Auth\Registrar as RegistrarContract;
use Kolimpri\Auth\Contracts\Repositories\UserRepository as UserRepositoryContract;
use Kolimpri\Auth\Contracts\Repositories\TeamRepository as TeamRepositoryContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->defineRoutes();
        });

        $this->defineResources();
    }

    /**
     * Define the Auth routes.
     *
     * @return void
     */
    protected function defineRoutes()
    {
        if (! $this->app->routesAreCached()) {
            $router = app('router');

            $router->group(['namespace' => 'Kolimpri\Auth\Http\Controllers'], function ($router) {
                require __DIR__.'/../Http/routes.php';
            });
        }
    }

    /**
     * Define the resources used by Auth.
     *
     * @return void
     */
    protected function defineResources()
    {
        $this->loadViewsFrom(AUTH_PATH.'/resources/views', 'auth');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                AUTH_PATH.'/resources/views' => base_path('resources/views/vendor/auth'),
            ], 'auth-full');

            $this->publishes([
                AUTH_PATH.'/resources/views/emails' => base_path('resources/views/vendor/auth/emails'),
                AUTH_PATH.'/resources/views/welcome.blade.php' => base_path('resources/views/vendor/auth/welcome.blade.php'),
                AUTH_PATH.'/resources/views/nav/guest.blade.php' => base_path('resources/views/vendor/auth/nav/guest.blade.php'),
                AUTH_PATH.'/resources/views/layouts/app.blade.php' => base_path('resources/views/vendor/auth/layouts/app.blade.php'),
                AUTH_PATH.'/resources/views/common/footer.blade.php' => base_path('resources/views/vendor/auth/common/footer.blade.php'),
                AUTH_PATH.'/resources/views/nav/authenticated.blade.php' => base_path('resources/views/vendor/auth/nav/authenticated.blade.php'),
                AUTH_PATH.'/resources/views/layouts/common/head.blade.php' => base_path('resources/views/vendor/auth/layouts/common/head.blade.php'),
                AUTH_PATH.'/resources/views/settings/tabs/profile.blade.php' => base_path('resources/views/vendor/auth/settings/tabs/profile.blade.php'),
                AUTH_PATH.'/resources/views/settings/tabs/security.blade.php' => base_path('resources/views/vendor/auth/settings/tabs/security.blade.php'),
                AUTH_PATH.'/resources/views/settings/team/tabs/owner.blade.php' => base_path('resources/views/vendor/auth/settings/team/tabs/owner.blade.php'),
                AUTH_PATH.'/resources/views/auth/registration/simple/basics.blade.php' => base_path('resources/views/vendor/auth/auth/registration/simple/basics.blade.php'),
                AUTH_PATH.'/resources/views/settings/team/tabs/membership/modals/edit-team-member.blade.php' => base_path('resources/views/vendor/auth/settings/team/tabs/membership/modals/edit-team-member.blade.php'),
            ], 'auth-basics');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('AUTH_PATH')) {
            define('AUTH_PATH', realpath(__DIR__.'/../../'));
        }

        if (! class_exists('kAuth')) {
            class_alias('Kolimpri\Auth\kAuth', 'kAuth');
        }

        config([
            'auth.password.email' => 'auth::emails.auth.password.email',
        ]);

        $this->defineServices();

        if ($this->app->runningInConsole()) {
            $this->commands([Install::class]);
        }
    }

    /**
     * Bind the Auth services into the container.
     *
     * @return void
     */
    protected function defineServices()
    {
        $services = [
            UserRepositoryContract::class => UserRepository::class,
            TeamRepositoryContract::class => TeamRepository::class,
        ];

        foreach ($services as $key => $value) {
            $this->app->bindIf($key, $value);
        }
    }
}
