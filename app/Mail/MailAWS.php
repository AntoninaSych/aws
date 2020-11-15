<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class MailAWS extends Mailable
{
    use Queueable, SerializesModels;

    public $img;
    public $mess;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subj, $img, $mess)
    {
        $this->img = $img;
        $this->mess = $mess;
        $this->subject = $subj;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('antoninasych@gmail.com')->view('emails.tpl');
    }
}
