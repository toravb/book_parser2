<?php


namespace App\Services;

use App\UserLastLocation;

class UserLocationService
{
    public function defaultLocation($userId, $location, $name)
    {
        $userLastLocation = new UserLastLocation();
        $userLastLocation->user_id = $userId;
        $userLastLocation->type = 'zip_code';
        $userLastLocation->point = $location;
        $userLastLocation->name = $name;
        $userLastLocation->save();
    }
}
