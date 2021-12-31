<?php

namespace App\AuthApi\Services;

class SocialAuthService
{
    public static function getSocialProviders()
    {
        return [
            'google',
            'vkontakte',
            'odnoklassniki',
            'yandex'
        ];
    }
}
