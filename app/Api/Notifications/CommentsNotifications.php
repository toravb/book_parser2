<?php

namespace App\Api\Notifications;

use App\Api\Events\NewAnswerOnCommentInBranchEvent;
use App\Api\Events\NewAnswerOnCommentNotificationEvent;
use App\Api\Events\NewLikeNotificationEvent;
use App\Api\Interfaces\NotificationInterface;
use App\Api\Models\NotificationUser;
use App\Api\Models\UserSettings;
use App\Models\User;

class CommentsNotifications implements NotificationInterface
{

    public $comment;
    public User $sender;

    public function __construct(
        private int    $senderUserId,
        private int    $commentId,
        private array  $notificationableTypes,
        private string $type,
        public string  $commentedType,
        public string  $createdAt,
        public int     $notificationId
    )
    {

        $this->comment = $this->notificationableTypes[$this->commentedType]::getNotificationComment($this->commentId);


        $this->sender = User::select('id', 'avatar', 'name')->findOrFail($this->senderUserId);

    }

    public function sendNotification()
    {
        if ($this->comment->parent_comment_id) {
            $parentComment = $this->notificationableTypes[$this->commentedType]::find($this->comment->parent_comment_id);

            if ($parentComment->user_id !== $this->sender->id) {
                $receiver = User::select('id', 'avatar', 'name')->find($parentComment->user_id);
                if ($receiver) {
                    $settings = UserSettings::where('user_id', $receiver->id)->first();
                    if ($settings->commented) {
                        $notificationUserModel = new NotificationUser();
                        $notificationUserModel->createRelation($receiver->id, $this->notificationId, NewAnswerOnCommentNotificationEvent::TYPE);
                        NewAnswerOnCommentNotificationEvent::dispatch([$receiver->id], $this->sender, $this->comment->getBookObject(), $this->createdAt, $this->comment->content);
                    }
                }
            }


            $otherUsersInBranch = $this->notificationableTypes[$this->commentedType]::where('parent_comment_id', $this->comment->parent_comment_id)
                ->where('id', '!=', $this->comment->id)->pluck('user_id');

            $notificationList = UserSettings::whereIn('user_id', $otherUsersInBranch)
                ->where('commentedOthers', '!=', false)
                ->where('user_id', '!=', $this->comment->user_id)
                ->pluck('user_id')->toArray();

            if (count($notificationList) !== 0) {
                $userNotifications = [];
                foreach ($notificationList as $userId) {
                    $userNotifications[] = [
                        'user_id' => $userId,
                        'notification_id' => $this->notificationId,
                        'read' => NotificationUser::UNREAD_NOTIFICATION,
                        'type' => NewAnswerOnCommentInBranchEvent::TYPE
                    ];
                }
                NotificationUser::insert($userNotifications);
                NewAnswerOnCommentInBranchEvent::dispatch($notificationList, $this->sender, $this->comment->getBookObject(), $this->createdAt, $this->comment->content);
            }
        }

    }
}
