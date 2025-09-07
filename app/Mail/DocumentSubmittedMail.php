<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Correctly concatenate the properties of the document object
        $subject = 'ASUU National Conference 2024 in Abuja: ' 
                   . $this->document->paper_type 
                   . ' - ' 
                   . $this->document->document_key;
        
        return $this->subject($subject)
                    ->view('emails.document-submitted')
                    ->with(['document' => $this->document]);
    }
    
}

