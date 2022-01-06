<?php

namespace App\Api\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProfileUpdateController
{
    public function store(UserUpdateRequest $request)
    {
        {
            $path = $request->file('avatar')->store('avatars');

            return $path;
        }
    }

    public function update(UserUpdateRequest $request)
    {

        $user = Auth::user();
        try {
            DB::beginTransaction();
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatar');
                $previousAvatar = $user->avatar;
                $user->avatar = $path;
            }

            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            $user->save();
            DB::commit();

            if (isset($previousAvatar)) {
                Storage::delete($previousAvatar);
            }

            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
