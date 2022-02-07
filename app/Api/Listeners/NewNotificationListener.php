<?php

namespace App\Api\Listeners;

use App\Api\Events\NewNotificationEvent;
use App\Api\Interfaces\Types;
use App\Api\Models\Notification;
use App\Api\Notifications\LikedComment;

class NewNotificationListener
{


    public function handle(NewNotificationEvent $event, Types $types, Notification $notification)
    {
        $notificationableModelTypes = $types->getNotificationTypes();
        $modelType = $notificationableModelTypes[$event->type];
        $notification->createNewNotification($event->userId, $modelType, $event->notificationableId);

        $class = $notificationableModelTypes[$event->type];


        if ($event->type === NewNotificationEvent::LIKED_COMMENT) {
            $notificationObject = new $class($event->userId, $event->notificationableId);
        }

        $class->sendNotification();
    }
}
