<?php

namespace App\Providers;

use App\Gate\IsAdminOrDoctor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Gate::define('isAuthorized',[IsAdminOrDoctor::class,'checkIsAdminOrDoctor']);
        Gate::define('isPatients', [\App\Gate\isPatients::class, 'checkIsPatients']);
        Gate::define('isDoctor', [\App\Gate\isDoctor::class, 'checkIsDoctor']);
        Gate::define('isAdmin', [\App\Gate\IsAdmin::class, 'checkIsAdmin']);
    }
}
