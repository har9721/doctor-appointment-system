<?php
namespace App\Gate;

class isPatients
{
    public function checkIsPatients($user)
    {
        if($user->role_ID == config('constant.patient_role_ID')) {
            return true;
        } else {
            return abort(403, "YOU ARE NOT AUTHORIZED!");
        }
    }
}
?>