<?php

namespace App\Gate;

use Illuminate\Support\Facades\Route;

class IsAdminOrDoctor
{
    public function checkIsAdminOrDoctor($user)
    {
        if(Route::currentRouteName() == 'admin.patientsUpdate' || in_array($user->role_ID, config('constant.admin_and_doctor_role_ids')))
        {
            return true;
        }else{
            return abort(403,"YOU ARE NOT AUTHORIZED!");
        }
    }
}