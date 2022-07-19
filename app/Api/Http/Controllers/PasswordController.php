<?php

namespace App\Api\Http\Controllers;


use App\Api\Http\Requests\ChangePasswordRequest;
use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Requests\ForgotPasswordRequest;
use App\AuthApi\Http\Requests\ResetPasswordRequest;
use App\AuthApi\Mails\PasswordForgotMail;
use App\AuthApi\Models\PasswordReset;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function change(ChangePasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return ApiAnswerService::successfulAnswer();
    }

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
