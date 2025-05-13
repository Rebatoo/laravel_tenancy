<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer('layouts.tenant.app', function ($view) {
            $view->with('customizations', tenant()->customizations ?? []);
        });
    }
} 