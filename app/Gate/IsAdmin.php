<?php
namespace App\Gate;

class IsAdmin
{
    public function checkIsAdmin($user)
    {
        if($user->role_ID == config('constant.admin_role_ID')) {
            return true;
        } else {
            return abort(403, "YOU ARE NOT AUTHORIZED!");
        }
    }
}
?>