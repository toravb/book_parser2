<?php

namespace App\AuthApi\Http\Controllers;

use App\Http\Controllers\Controller;
use App\AuthApi\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $loginData = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!auth()->attempt($loginData)) {
            return response()->json(
                [
                    'message' => 'Введены неверные данные.',
                    'errors' => ['email' =>
                        ['Неверный email или пароль. Пожалуйста введите верные данные.']
                    ],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else {
            $user = auth()->user();
            $accessToken = $user->createToken('authToken')->accessToken;

            return response()->json([
                'token' => $accessToken
            ]);
        }
    }
}
