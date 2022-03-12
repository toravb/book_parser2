<?php

namespace App\Api\Models;

use App\Api\Events\NewNotificationEvent;
use App\Models\User;
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

        return $this;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function notificationable()
    {
        return $this->morphTo();
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'avatar');
    }

}
