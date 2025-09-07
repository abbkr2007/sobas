<?php
namespace App\Mail;


use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Status_Update extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $document;
    public $fullName;

    public function __construct($user, $document, $fullName)
    {
        $this->user = $user;
        $this->document = $document;
        $this->fullName = $fullName;
    }

    public function build()
    {
        // Data to pass to the PDF view
        $data = [
            'document' => $this->document,
            'userName' => $this->fullName,
        ];

        // Generate the PDF
        $pdf = PDF::loadView('emails.pdf.letter', $data);

        // Build the email with PDF attachment
        return $this->subject('ASUU National Conference 2024 in Abuja: Abstract Acceptance Notification')
                    ->view('emails.paper_status_update')
                    ->with($data)
                    ->attachData($pdf->output(), 'abstract-acceptance-notification.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}



