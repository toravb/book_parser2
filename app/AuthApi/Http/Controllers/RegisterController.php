<?php

namespace App\AuthApi\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistryRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController
{
    public function registry(RegistryRequest $request)
    {
        $user = User::create([
            'email' => mb_strtolower($request->email),
            'password' => Hash::hashPassword($request->password),
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'token' => $accessToken,
        ]);
    }
}
