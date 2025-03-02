<?php

namespace App\Gate;

class IsAdminOrDoctor
{
    public function checkIsAdminOrDoctor($user)
    {
        if(in_array($user->role_ID, config('constant.admin_and_doctor_role_ids')))
        {
            return true;
        }else{
            return abort(403,"YOU ARE NOT AUTHORIZED!");
        }
    }
}