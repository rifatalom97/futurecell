<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $order_number;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$order_number)
    {
        $this->user         = $user;
        $this->order_number = $order_number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Order confirmation from '.config('app.name');
        return $this->subject($subject)
                ->view('email.customer_order_confirm')
                ->with('user',[
                    'name'          => $this->user->name,
                    'order_number'  => $this->order_number,
                    'subject'       => $subject
                ]);
    }
}
