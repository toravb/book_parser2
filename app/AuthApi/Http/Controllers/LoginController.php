<?php

namespace App\AuthApi\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', mb_strtolower($request->email))->first();
        if ($user === null) {
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
            $accessToken = $user->createToken('authToken')->accessToken;

            return response()->json([
                'token' => $accessToken
            ]);
        }
    }
}
