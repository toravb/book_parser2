<?php

namespace App\Api\Notifications;

use App\Api\Events\NewLikeNotificationEvent;
use App\Api\Events\NewQuoteLikeNotificationEvent;
use App\Api\Interfaces\NotificationInterface;
use App\Api\Models\NotificationUser;
use App\Api\Models\UserSettings;
use App\Models\User;

class LikedQuoteNotification implements NotificationInterface
{
    public $quote;
    public User $sender;

    public function __construct(
        private int    $senderUserId,
        private int    $quoteId,
        private array  $notificationableTypes,
        private string $type,
        public string  $likedType,
        public string  $createdAt,
        public int     $notificationId
    )
    {
        $this->quote = $this->notificationableTypes[$this->type][$this->likedType]::getNotificationQuote($this->quoteId);

        $this->sender = User::select('id', 'avatar', 'name')->findOrFail($this->senderUserId);
    }

    public function sendNotification()
    {
        if ($this->quote->user_id !== $this->sender->id) {
            $settings = UserSettings::where('user_id', $this->quote->user_id)->first();
            if ($settings->likes) {
                $notificationUserModel = new NotificationUser();
                $notificationUserModel->createRelation($this->quote->user_id, $this->notificationId, NewQuoteLikeNotificationEvent::TYPE);
                NewQuoteLikeNotificationEvent::dispatch([$this->quote->user_id], $this->sender, $this->quote->getQuoteObject(), $this->createdAt);
            }
        }
    }
}
