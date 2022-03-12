<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\FeedbackFormRequest;
use App\Api\Services\ApiAnswerService;
use App\AuthApi\Mails\FeedbackFormMail;
use App\Http\Controllers\Controller;
use App\Models\FeedbackForm;
use App\Models\FeedbackFormAttachment;
use Illuminate\Support\Facades\Mail;


class FeedbackFormsController extends Controller
{
    public function create(FeedbackFormRequest $request, FeedbackForm $form)
    {
        $form->saveFromRequest($request);

        Mail::to(config('mail.support'))->send(new FeedbackFormMail($form));

        return ApiAnswerService::successfulAnswerWithData([
            'form' => $form,
        ]);
    }
}
