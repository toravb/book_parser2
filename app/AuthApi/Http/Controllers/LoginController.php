<?php

namespace App\AuthApi\Http\Controllers;

use App\Http\Controllers\Controller;
use App\AuthApi\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $loginData = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($loginData)) {
            $user = auth()->user();
            if ($user->hasVerifiedEmail()) {
                $accessToken = $user->createToken('authToken')->accessToken;

                return response()->json([
                    'token' => $accessToken
                ]);
            }
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
