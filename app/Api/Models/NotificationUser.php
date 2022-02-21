<?php

namespace App\Api\Models;

use App\Api\Events\NewAnswerOnCommentNotificationEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;

    protected $table = 'notification_user';

    public $timestamps = false;

    const READ_NOTIFICATION = true;
    const UNREAD_NOTIFICATION = false;

    protected $fillable = [
        'user_id',
        'notification_id',
        'read',
        'type'
    ];

    public function createRelation(int $receiverId, int $notificationId, string $type) {
        $this->user_id = $receiverId;
        $this->notification_id = $notificationId;
        $this->read = NotificationUser::UNREAD_NOTIFICATION;
        $this->type = $type;
        $this->save();
    }
}
