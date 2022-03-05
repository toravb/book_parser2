<?php

namespace App\Api\Listeners;

use App\Api\Events\NewNotificationEvent;
use App\Api\Interfaces\Types;
use App\Api\Models\Notification;
use App\Api\Notifications\LikedComment;

class NewNotificationListener
{

    public function __construct(public Types $types, public Notification $notification)
    {

    }


    public function handle(NewNotificationEvent $event)
    {
        $notificationableModelTypes = $this->types->getNotificationTypes();


        $modelType = $notificationableModelTypes[$event->type][$event->notificationableType];

        $notification = $this->notification->createNewNotification($event->userId, $modelType, $event->notificationableId);

        $notificationableHandlers = $this->types->getNotificationHandleObjects();
        $notificationHandlerClass = $notificationableHandlers[$event->type];

        if ($event->type === NewNotificationEvent::LIKED_COMMENT) {
            $notificationHandler = new $notificationHandlerClass(
                $event->userId,
                $event->notificationableId,
                $this->types->getNotificationTypes(),
                $event->type,
                $event->notificationableType,
                $notification->created_at,
                $notification->id
            );
        }

        if($event->type === NewNotificationEvent::ANSWER_ON_COMMENT_AND_ALSO_COMMENTED) {
            $notificationHandler = new $notificationHandlerClass(
                $event->userId,
                $event->notificationableId,
                $this->types->getCommentTypes(),
                $event->type,
                $event->notificationableType,
                $notification->created_at,
                $notification->id
            );
        }

        $notificationHandler->sendNotification();
    }
}
