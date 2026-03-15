<?php

namespace App\Observers;

use App\Jobs\SendPaymentSummary;
use App\Models\Appointments;
use App\Models\PaymentDetails;

class PaymentDetailsObserver
{
    public function created(PaymentDetails $paymentDetails)
    {
        if($paymentDetails->status == 'partial' || $paymentDetails->status == 'completed')
        {   
            info('------------------------paymentDetailsObserver created method--------------------');
            info($paymentDetails->appointment_ID);
            $paymentSummary = Appointments::getPaymentSummary($paymentDetails->appointment_ID);
            info($paymentSummary);
            dispatch(new SendPaymentSummary($paymentSummary));
        }
    }

    public function updated(PaymentDetails $paymentDetails)
    {
        if($paymentDetails->wasChanged('status') && in_array($paymentDetails->status, ['partial','completed']))
        {
            info('------------------------paymentDetailsObserver updated method--------------------');
            info($paymentDetails->appointment_ID);
            $paymentSummary = Appointments::getPaymentSummary($paymentDetails->appointment_ID);
            info($paymentSummary);
            dispatch(new SendPaymentSummary($paymentSummary));
        }
    }
}
