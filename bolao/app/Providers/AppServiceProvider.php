<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components.alert', 'alert_component');
        Blade::component('components.breadcrumb', 'breadcrumb_component');
        Blade::component('components.search', 'search_component');
        Blade::component('components.table', 'table_component');
        Blade::component('components.table_site', 'table_site_component');
        Blade::component('components.paginate', 'paginate_component');
        Blade::component('components.page', 'page_component');
        Blade::component('components.form', 'form_component');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Contracts\UserRepositoryInterface', 'App\Repositories\Eloquent\UserRepository');
        $this->app->bind('App\Repositories\Contracts\PermissionRepositoryInterface', 'App\Repositories\Eloquent\PermissionRepository');
        $this->app->bind('App\Repositories\Contracts\RoleRepositoryInterface', 'App\Repositories\Eloquent\RoleRepository');
        $this->app->bind('App\Repositories\Contracts\BettingRepositoryInterface', 'App\Repositories\Eloquent\BettingRepository');
        $this->app->bind('App\Repositories\Contracts\RoundRepositoryInterface', 'App\Repositories\Eloquent\RoundRepository');
        $this->app->bind('App\Repositories\Contracts\MatchRepositoryInterface', 'App\Repositories\Eloquent\MatchRepository');
    }
}
