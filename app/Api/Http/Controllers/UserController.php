<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller

{
    public function destroy()
    {

        $user = Auth::user();
        User::where('id', $user->id)
            ->update([
                'name' => null,
                'email' =>null,
                'password' => null
             ]);
        return ApiAnswerService::successfulAnswer();
    }

}
