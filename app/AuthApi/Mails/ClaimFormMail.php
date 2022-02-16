<?php

namespace App\AuthApi\Mails;

use App\Models\FeedbackForm;


class ClaimFormMail extends \Illuminate\Mail\Mailable
{
    public FeedbackForm $form;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(FeedbackForm $form)
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
