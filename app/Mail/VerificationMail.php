<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\OptionsController;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp  = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Verification otp from '.config('app.name');
        return $this->subject($subject)
                ->view('email.customer_verification')
                ->with('user',[
                    'name'  => $this->user->name,
                    'otp'   => $this->otp,
                    'subject' => $subject
                ]);
    }
}
