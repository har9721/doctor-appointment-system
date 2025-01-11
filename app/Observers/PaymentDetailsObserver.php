<?php

namespace App\Observers;

use App\Jobs\SendPaymentSummary;
use App\Models\Appointments;
use App\Models\PaymentDetails;

class PaymentDetailsObserver
{
    public function created(PaymentDetails $paymentDetails)
    {
        info('------------------------id--------------------');
        info($paymentDetails->appointment_ID);
        $paymentSummary = Appointments::getPaymentSummary($paymentDetails->appointment_ID);
        info($paymentSummary);
        dispatch(new SendPaymentSummary($paymentSummary));
    }
}
