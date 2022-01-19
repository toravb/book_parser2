<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {

        $user = Auth::user();

        return UserService::userAvatar($user);;
    }
}
