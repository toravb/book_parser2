<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\ProfileUpdateRequest;
use App\Api\Services\ApiAnswerService;
use App\Api\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileUpdateController extends Controller
{
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();
            if ($request->avatar) {
                $path = Storage::put('avatar', $request->avatar);

                if (isset($user->avatar)) Storage::delete($user->avatar);

                $user->avatar = $path;
            }

            $user->email = $request->email;
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->save();

            DB::commit();

            return ApiAnswerService::successfulAnswerWithData($user);
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();

            return ApiAnswerService::errorAnswer('Something went wrong');
        }
    }
}
