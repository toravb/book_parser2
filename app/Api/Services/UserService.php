<?php

namespace App\Api\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public static function userAvatar (User $user){
        if ($user->avatar !== null) {
            $url = url('/');
            $user->avatar = $url . Storage::url($user->avatar) ;
        }
        return $user;
    }
}
