<?php

namespace App\Api\Models;

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
        'read'
    ];
}
