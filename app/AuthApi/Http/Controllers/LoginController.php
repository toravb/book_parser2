<?php

namespace App\AuthApi\Http\Controllers;

use App\AuthApi\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (auth()->attempt($request->only(['email', 'password']))) {
            $user = auth()->user();
            if ($user->hasVerifiedEmail()) {
                $accessToken = $user->createToken('authToken')->accessToken;

                return response()->json([
                    'token' => $accessToken
                ]);
            }
        }

        if (User::withTrashed()->where($request->only('email'))->exists()) {
            return response()->json(
                [
                    'message' => 'Введены неверные данные.',
                    'errors' => [
                        'email' =>
                            ['Пользователь с такой почтой уже зарегистрирован но был удалён.']
                    ],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return response()->json(
            [
                'message' => 'Введены неверные данные.',
                'errors' => ['email' =>
                    ['Неверный email или пароль. Пожалуйста введите верные данные.']
                ],
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }


    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }
}
