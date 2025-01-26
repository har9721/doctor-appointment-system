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

class sendPaymentDone extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($data)
    {
        $this->mailData = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Payment Successful Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.sendPaymentDone',
            with: [
                'doctorName' => $this->mailData['doctorName'] ?? '',
                'patientName' => $this->mailData['patientName'] ?? '',
                'date' => $this->mailData['appointmentDate'] ?? '',
                'time' => $this->mailData['time'] ?? '',
                'transaction_id' => $this->mailData['transaction_id'] ?? '',
                'paymentDate' => $this->mailData['paymentDate'] ?? '',
                'amount' => $this->mailData['amount'] ?? ''
            ],
        );
    }

    public function attachments()
    {
        $pdf = PDF::loadView('invoices.invoice', ['invoiceData' => $this->mailData]);

        $filePath = 'public/invoices/invoice_' . $this->mailData['transaction_id'] . '.pdf';
    
        Storage::put($filePath,$pdf->output());

        $fullFilePath = storage_path('app/'.$filePath);

        return [
            Attachment::fromPath($fullFilePath)->as('invoice.pdf')->withMime('application/pdf'),
        ];
    }
}
