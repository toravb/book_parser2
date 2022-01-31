<?php

namespace App\Api\Http\Controllers;


use App\Api\Http\Requests\ChangePasswordRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function changePassword(ChangePasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return ApiAnswerService::successfulAnswer();
    }
}
