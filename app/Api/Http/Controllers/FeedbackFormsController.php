<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\FeedbackFormRequest;
use App\Api\Services\ApiAnswerService;
use App\AuthApi\Mails\FeedbackFormMail;
use App\AuthApi\Mails\PasswordForgotMail;
use App\Http\Controllers\Controller;
use App\Models\FeedbackForm;
use App\Models\FeedbackFormImage;
use App\Models\ImageFeedbackForm;
use Illuminate\Support\Facades\Mail;


class FeedbackFormsController extends Controller
{
    public function create(FeedbackFormRequest $request, FeedbackForm $form)
    {
        $form->create($request);

        $attachments = collect();
        foreach ($request->attachments ?? [] as $file) {
            $imageForm = new FeedbackFormImage();
            $imageForm->feedback_form_id = $form->id;

            $imageForm->create($file);

            $attachments->add($imageForm);
        }

        Mail::to(config('mail.support'))->send(new FeedbackFormMail($form, $attachments));


        return ApiAnswerService::successfulAnswerWithData([
            'form' => $form,
            'attachments' => $attachments,
        ]);
    }
}
