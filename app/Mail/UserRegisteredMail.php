<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $payment;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Payment $payment
     */
    public function __construct($user, $payment)
    {
        $this->user = $user;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Portal Of School Of Basic And Advanced Studies - Registration Slip')
                    ->view('emails.user-registered-slip')    // the email Blade
                    ->with([
                        'user' => $this->user,
                        'payment' => $this->payment,
                    ]);
    }
}
