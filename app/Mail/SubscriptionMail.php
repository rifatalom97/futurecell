<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $unsubscribe;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$unsubscribe = false)
    {
        $this->user         = $user;
        $this->unsubscribe  = $unsubscribe;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = 'You are subscribed to '.config('app.name') . ' newsletters';
        if($this->unsubscribe){
            $subject = 'You are unsubscribed from '.config('app.name') . ' newsletters';
        }
        return $this->subject($subject)
                ->view('email.customer_suscription')
                ->with('user',[
                    'name'          => $this->user->name,
                    'subject'       => $subject,
                    'unsubscribe'   => $this->unsubscribe,
                ]);
    }
}
