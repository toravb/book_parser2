<?php

namespace App\AuthApi\Http\Controllers;

use App\Api\Models\UserSettings;
use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Requests\RegistryRequest;
use App\AuthApi\Mails\VerifyMail;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function registry(RegistryRequest $request, User $userModel, UserSettings $userSettings)
    {
        try {
            DB::beginTransaction();
            $user = $userModel->createUser($request->email, $request->password, null, true);
            $userSettings->create($user->id, true, true, false);

            Mail::to($user->email)->send(new VerifyMail($user->verify_token, $user->email));

            DB::commit();
            return ApiAnswerService::successfulAnswer();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return ApiAnswerService::errorAnswer('Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
