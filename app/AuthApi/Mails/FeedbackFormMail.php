<?php

namespace App\AuthApi\Mails;

use App\Models\FeedbackForm;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class FeedbackFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public FeedbackForm $form;
    public Collection $formAttachments;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(FeedbackForm $form)
    {
        $this->form = $form;
        $this->formAttachments = $form->attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this
            ->subject('Новая заявка с формы обратной связи')
            ->view('emails.support_feedback');

        foreach ($this->formAttachments as $formAttachment) {
            $mail->attachFromStorage($formAttachment->storage_path);
        }

        return $mail;
    }
}
