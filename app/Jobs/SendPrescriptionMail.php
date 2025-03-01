<?php

namespace App\Jobs;

use App\Mail\SendPrescriptionEmail;
use App\Models\Prescriptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPrescriptionMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    protected $prescription_data;
    protected $mode;

    public function __construct($data,$mode)
    {
        $this->prescription_data = $data;
        $this->mode = $mode;
    }

    public function handle()
    {
        info('------------------------------------inside the job---------------------------------------------');
        info($this->prescription_data);
        info('------------------------------------end the job---------------------------------------------');

        $getPatientsData = Prescriptions::getPrescriptionDetails($this->prescription_data['appointment_ID']);

        if(!empty($getPatientsData))
        {
            Mail::to($getPatientsData['email'])
            ->send(
                new SendPrescriptionEmail(
                    $getPatientsData,
                    $this->mode
                )
            );
        }
    }
}
