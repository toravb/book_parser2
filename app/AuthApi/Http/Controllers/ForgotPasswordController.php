<?php

namespace App\AuthApi\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Requests\ForgotPasswordRequest;
use App\AuthApi\Mails\PasswordForgotMail;
use App\AuthApi\Models\PasswordReset;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgot(ForgotPasswordRequest $request, PasswordReset $passwordReset): \Illuminate\Http\JsonResponse
    {
        if (User::where('email', $request->email)->exists()) {
            $passwordReset->deleteRecord($request->email);
            $passwordReset = $passwordReset->create($request->email);
            Mail::to($request->email)->send(new PasswordForgotMail($passwordReset->token, $request->email));
        }

        return ApiAnswerService::successfulAnswer();
    }
}
