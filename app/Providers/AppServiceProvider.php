<?php

namespace App\Providers;

use App\Gate\IsAdminOrDoctor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('isAuthorized',[IsAdminOrDoctor::class,'checkIsAdminOrDoctor']);
    }
}
