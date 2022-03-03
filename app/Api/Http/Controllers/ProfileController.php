<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\ProfileUpdateRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class ProfileController extends Controller
{
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $user = User::with(['userSettings'])->findOrFail(auth()->id());

        return ApiAnswerService::successfulAnswerWithData($user);
    }

    public function update(ProfileUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();
            if ($request->avatar) {
                if (isset($user->avatar)) Storage::delete($user->avatar);

                $user->avatar = Storage::put('avatar', $request->avatar);
            }

            $user->email = $request->email;
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->nickname= $request->nickname;
            $user->save();

            DB::commit();

            return ApiAnswerService::successfulAnswerWithData($user);
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();

            return ApiAnswerService::errorAnswer('Something went wrong');
        }
    }

    public function destroy(): \Illuminate\Http\JsonResponse
    {
        $tokens = \auth()->user()->tokens()->pluck('id');

        Token::whereIn('id', $tokens)->delete();
        RefreshToken::whereIn('access_token_id', $tokens)->delete();

        \auth()->user()->delete();

        return ApiAnswerService::successfulAnswer();
    }
}
