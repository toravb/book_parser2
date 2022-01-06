<?php

namespace App\AuthApi\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Requests\ResetPasswordRequest;
use App\AuthApi\Models\PasswordReset;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request, PasswordReset $passwordReset)
    {
        $reset = $passwordReset->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        if ($reset !== null) {
            $user = User::where('email', $request->email)->firstOrFail();
            $user->password = Hash::make($request->password);
            $user->email_verified_at = Carbon::now();
            $user->save();
            $reset->deleteRecord($request->email);
            return ApiAnswerService::successfulAnswer();
        } else {
            return ApiAnswerService::errorAnswer('Вы не прошли процедуру восстановления пароля', Response::HTTP_FORBIDDEN);
        }
    }
}
