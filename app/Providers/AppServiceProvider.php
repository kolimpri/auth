<?php

namespace Kolimpri\Auth\Providers;

use Kolimpri\Auth\kAuth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (method_exists($this, 'customizeAuth')) {
            $this->customizeAuth();
        }

        if (method_exists($this, 'customizeRegistration')) {
            $this->customizeRegistration();
        }

        if (method_exists($this, 'customizeRoles')) {
            $this->customizeRoles();
        }

        if (method_exists($this, 'customizeProfileUpdates')) {
            $this->customizeProfileUpdates();
        }

        if (method_exists($this, 'customizeSettingsTabs')) {
            $this->customizeSettingsTabs();
        }

        kAuth::generateInvoicesWith($this->invoiceWith);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
