<?php

namespace App\Api\Notifications;

use App\Api\Events\NewLikeNotificationEvent;
use App\Api\Interfaces\NotificationInterface;
use App\Api\Interfaces\Types;
use App\Models\User;

class LikedComment implements NotificationInterface
{
    public $comment;
    public User $sender;

    public function __construct(private int $senderUserId, private int $commentId, private array $notificationableTypes, private string $type, public string $likedType, public string $createdAt)
    {

        $this->comment = $this->notificationableTypes[$this->type][$this->likedType]::with([
            'books' => function ($query) {
                return $query->select('id', 'title');
            }
        ])
            ->findOrFail($this->commentId);

        $this->sender = User::select('id', 'avatar', 'name')->findOrFail($this->senderUserId);

    }

    public function sendNotification()
    {
        NewLikeNotificationEvent::dispatch([$this->comment->user_id], $this->sender, $this->comment->books, $this->createdAt);
    }
}
