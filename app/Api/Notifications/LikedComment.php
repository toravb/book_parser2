<?php

namespace App\Api\Notifications;

use App\Api\Events\NewLikeNotificationEvent;
use App\Api\Interfaces\NotificationInterface;
use App\Api\Models\NotificationUser;
use App\Api\Models\UserSettings;
use App\Models\User;

class LikedComment implements NotificationInterface
{
    public $comment;
    public User $sender;

    public function __construct(
        private int $senderUserId,
        private int $commentId,
        private array $notificationableTypes,
        private string $type,
        public string $likedType,
        public string $createdAt,
        public int $notificationId
    )
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
        if($this->comment->user_id !== $this->sender->id) {
            $settings = UserSettings::where('user_id', $this->comment->user_id)->first();
            if ($settings->likes) {
                $notificationUserModel = new NotificationUser();
                $notificationUserModel->createRelation($this->comment->user_id, $this->notificationId, NewLikeNotificationEvent::TYPE);
                NewLikeNotificationEvent::dispatch([$this->comment->user_id], $this->sender, $this->comment->books, $this->createdAt);
            }
        }
    }
}
