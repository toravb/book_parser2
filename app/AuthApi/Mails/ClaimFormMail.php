<?php

namespace App\AuthApi\Mails;

use App\Models\ClaimForm;
use App\Models\FeedbackForm;


class ClaimFormMail extends \Illuminate\Mail\Mailable
{
    public ClaimForm $form;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ClaimForm $form)
    {
        $this->form = $form;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this
            ->subject('Новая жалоба на материал')
            ->view('emails.claim_feedback');

        return $mail;
    }

}
