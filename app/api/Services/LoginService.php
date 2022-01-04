<?php

namespace App\api\Services;

use App\api\Exceptions\LoginException;
use Illuminate\Http\Response;

class LoginService
{
    public function login(string $email, string $password)
    {

        $loginData = [
            'email' => $email,
            'password' => $password
        ];

        if (!auth()->attempt($loginData)) {
           throw new LoginException('The give data was invalid.');
        }
        $user = auth()->user();

        return  $user->createToken('authToken')->accessToken;
    }
}
