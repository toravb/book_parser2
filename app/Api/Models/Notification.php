<?php

namespace App\Api\Models;

use App\Api\Events\NewNotificationEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function createNewNotification(int $userId, string $type, int $notificationableId): Notification
    {
        $this->user_id = $userId;
        $this->notificationable_type = $type;
        $this->notificationable_id = $notificationableId;
        $this->save();

//        if ($type === NewNotificationEvent::LIKED_COMMENT) {
//            $commentModel = new  $types[$type];
//            $comment = $commentModel->find($notificationableId);
//            NotificationUser::create([
//                'user_id' => $userId,
//                'notification_id' => $this->id,
//                'read' => NotificationUser::UNREAD_NOTIFICATION
//            ]);
//        }

        NotificationUser::create([
            'user_id' => $userId,
            'notification_id' => $this->id,
            'read' => NotificationUser::UNREAD_NOTIFICATION
        ]);

        return $this;
    }
}
