<?php

namespace App\AuthApi\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordForgotMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public string $token, public string $email)
    {
        $frontUrl = config('app.front_url');
        $this->url = $frontUrl . '?modalType=password_forgot&token=' . $token . '&email=' . $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_forgot');
    }
}
