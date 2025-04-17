<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendPrescriptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $mode;
   
    public function __construct($prescriptionData,$mode)
    {
        info('----------------------------------------mode----------------------------------------------------');
        info($this->mode);
        $this->data = $prescriptionData;
        $this->mode = $mode;
    }

    public function envelope()
    {
        return new Envelope(
            subject: ($this->mode === 'add') ? 'Appointment Prescription Mail' : 'Appointment Prescription Update Mail',
        );
    }

    public function content()
    {
        info('----------------------------data---------------------------------');
        info($this->data);

        return new Content(
            view: 'mail.sendPrescriptionEmail',
            with: [
                'doctor_name' => $this->data['doctorName'],
                'patient_name' => $this->data['patientName'],
                'appointment_date' => date("d-m-Y",strtotime($this->data['appointmentDate'])),
                'medicines' => $this->data['medicines'],
                'instructions' => $this->data['instructions'],
            ],
        );
    }

    public function attachments()
    {
        $prescription_data = $this->data['medicines'];
        $instructions = $this->data['instructions'];
        $pdf = PDF::loadView('prescriptions.prescription', [
            'data' => $prescription_data,
            'instructions' => $instructions
        ]);

        $filePath = 'public/Prescriptions/prescrip_' . $this->data['id'] . '.pdf';
    
        Storage::put($filePath,$pdf->output());

        $fullFilePath = storage_path('app/'.$filePath);

        return [
            Attachment::fromPath($fullFilePath)->as('prescription.pdf')->withMime('application/pdf'),
        ];
    }
}
