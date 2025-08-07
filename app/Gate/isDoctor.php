<?php
namespace App\Gate;

class isDoctor
{
    public function checkIsDoctor($user)
    {
        if($user->role_ID == config('constant.doctor_role_ID')) {
            return true;
        }else {
            return abort(403, "YOU ARE NOT AUTHORIZED!");
        }
    }
}
?>