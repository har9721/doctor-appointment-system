<?php  
    return [
        'doctor_role_ID' => 2,
        'patients_role_ID' => 3,
        'admin_role_ID' => 1,
        'admin_and_doctor_role_ids' => [1,2],
        'admin_and_patients_role_ids' => [1,3],

        'payment_expiry_time' => env('payment_expiry_time', 60)
    ];
?>
