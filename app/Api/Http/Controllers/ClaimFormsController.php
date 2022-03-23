<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\ClaimFormRequest;
use App\Api\Services\ApiAnswerService;
use App\AuthApi\Mails\ClaimFormMail;
use App\Http\Controllers\Controller;
use App\Models\ClaimForm;
use Illuminate\Support\Facades\Mail;


class ClaimFormsController extends Controller
{
    public function store(ClaimFormRequest $request, ClaimForm $form)
    {
        $form->create($request);

        Mail::to(config('mail.support'))->send(new ClaimFormMail($form));

        return ApiAnswerService::successfulAnswerWithData($form);
    }
}
