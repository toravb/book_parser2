<?php

namespace App\Api\Notifications;

use App\Api\Events\NewLikeNotificationEvent;
use App\Api\Interfaces\NotificationInterface;
use App\Api\Models\NotificationUser;
use App\Api\Models\UserSettings;
use App\Models\User;

class LikedReview implements NotificationInterface
{
    public $review;
    public User $sender;

    public function __construct(
        private int    $senderUserId,
        private int    $reviewId,
        private array  $notificationableTypes,
        private string $type,
        public string  $likedType,
        public string  $createdAt,
        public int     $notificationId
    )
    {
        $this->review = $this->notificationableTypes[$this->type][$this->likedType]::getNotificationReview($this->reviewId);

        $this->sender = User::select('id', 'avatar', 'name')->findOrFail($this->senderUserId);
    }

    public function sendNotification()
    {
        if ($this->review->user_id !== $this->sender->id) {
            $settings = UserSettings::where('user_id', $this->review->user_id)->first();
            if ($settings->likes) {
                $notificationUserModel = new NotificationUser();
                $notificationUserModel->createRelation($this->review->user_id, $this->notificationId, NewLikeNotificationEvent::TYPE);
                NewLikeNotificationEvent::dispatch([$this->review->user_id], $this->sender, $this->review->getBookObject(), $this->createdAt);
            }
        }
    }
}
