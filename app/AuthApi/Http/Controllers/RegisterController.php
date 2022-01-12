<?php

namespace App\AuthApi\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Requests\RegistryRequest;
use App\AuthApi\Mails\VerifyMail;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function registry(RegistryRequest $request, User $userModel)
    {
        try {
            DB::beginTransaction();
            $user = $userModel->createUser($request->email, $request->password, null, true);
            DB::table('user_settings')->insert([
                'user_id' => $userModel->id,
                'likes' => true,
                'commented' => true,
                'commentedOthers' => false
            ]);
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
