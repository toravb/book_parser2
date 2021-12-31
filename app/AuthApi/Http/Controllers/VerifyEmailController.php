<?php

namespace App\AuthApi\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Requests\VerifyEmailRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class VerifyEmailController extends Controller
{
    public function verify(VerifyEmailRequest $request)
    {
        $user = User::where('verify_token', $request->token)
            ->where('email', $request->email)
            ->firstOrFail();
        $user->email_verified_at = Carbon::now();
        $user->save();
        return ApiAnswerService::successfulAnswer();
    }
}
