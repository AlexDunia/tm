<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    // public $token;

    // public function __construct($token)
    // {
    //     $this->token = $token;
    // }

    /**
     * Get the message envelope.
     */


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emailtemplate');
    }

}
