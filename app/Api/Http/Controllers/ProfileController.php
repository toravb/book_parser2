<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {

        $user = Auth::user();

        if ($user->avatar !== null) {
            $url = url('/');
            $user->avatar = $url . Storage::url($user->avatar) ;
        }


        return $user;
    }
}
