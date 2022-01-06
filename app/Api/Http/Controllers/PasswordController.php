<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;


class PasswordController extends Controller
{


    public function resetPassword($user, $password) {

            $this->setUserPassword($user, $password);


    }

    protected function setUserPassword($user, $password) {
        $user->password = Hash::make($password);
        $user->save();
    }



}
