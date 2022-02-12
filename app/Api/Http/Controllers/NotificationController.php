<?php

namespace App\Api\Http\Controllers;

use App\Api\Models\Notification;
use App\Api\Models\NotificationUser;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationController extends Controller
{
    const NOTIFICATION_COUNT = 5;

    public function get()
    {
        $userId = Auth::id();

        $notifications = Notification::join('notification_user', function ($join) use ($userId) {
            $join->on('notification_user.notification_id', '=', 'notifications.id')
                ->where('notification_user.user_id', $userId)
                ->where('read', NotificationUser::UNREAD_NOTIFICATION);
        })
            ->with(['notificationable' => function (MorphTo $morphTo) {
                $morphTo
                    ->morphWith([
                        BookComment::class => ['books' => function ($query) {
                            $query->select('id', 'title');
                        }]
                    ]);
            },
                'sender'])
            ->latest()
            ->simplePaginate(self::NOTIFICATION_COUNT);

        return ApiAnswerService::successfulAnswerWithData($notifications);


    }
}
