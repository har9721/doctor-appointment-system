<?php

namespace App\Observers;

use App\Jobs\SendPaymentSummary;
use App\Models\Appointments;
use App\Models\PaymentDetails;

class PaymentDetailsObserver
{
    public function created(PaymentDetails $paymentDetails)
    {
        if($paymentDetails->status == 'completed')
        {   
            info('------------------------id--------------------');
            info($paymentDetails->appointment_ID);
            $paymentSummary = Appointments::getPaymentSummary($paymentDetails->appointment_ID);
            info($paymentSummary);
            dispatch(new SendPaymentSummary($paymentSummary));
        }
    }

    public function updated(PaymentDetails $paymentDetails)
    {
        if($paymentDetails->wasChanged('status') && $paymentDetails->status == 'completed')
        {
            info('------------------------updated--------------------');
            info($paymentDetails->appointment_ID);
            $paymentSummary = Appointments::getPaymentSummary($paymentDetails->appointment_ID);
            info($paymentSummary);
            dispatch(new SendPaymentSummary($paymentSummary));
        }
    }
}
