<?php

namespace App\AuthApi\Http\Controllers;

use App\AuthApi\Http\Requests\RegistryRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController
{
    public function registry(RegistryRequest $request)
    {
        $user = User::create([
            'name' => 'asda',
            'email' => mb_strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'token' => $accessToken,
        ]);
    }
}
