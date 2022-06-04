<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$password='')
    {
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Thank you for registration on '.config('app.name');

        if($this->password){
            return $this->subject($subject)
                ->view('email.customer_registration2')
                ->with('user',[
                    'name'          => $this->user->name,
                    'subject'       => $subject,
                    'password'      => $this->password,
                ]);
        }

        return $this->subject($subject)
                ->view('email.customer_registration')
                ->with('user',[
                    'name'          => $this->user->name,
                    'subject'       => $subject
                ]);
    }
}
