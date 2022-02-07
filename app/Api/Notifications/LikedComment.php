<?php

namespace App\Api\Notifications;

use App\Api\Interfaces\NotificationInterface;

class LikedComment implements NotificationInterface
{

    public function __construct(private int $commentId)
    {

    }

    public function sendNotification()
    {

    }
}
