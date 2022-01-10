<?php

namespace App\Api\Http\Controllers;


use App\Api\Http\Requests\PasswordRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{


    public function resetPassword(PasswordRequest $request)
    {


        $user = Auth::user();
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password)
        ]);
//        $user->password = Hash::make($request->password);
//
//        $user->save();
        return ApiAnswerService::successfulAnswer();
    }


}
