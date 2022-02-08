<?php

namespace App\Api\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const LIKED_COMMENT = 'liked_comment';
    const ANSWER_ON_COMMENT = 'answer_on_comment';
    const ALSO_COMMENTED = 'also_commented';
    public string $type;
    public int $notificationableId;
    public int $userId;

    public function __construct(string $type, public string $notificationableType, int $notificationableId, int $userId)
    {
        $this->type = $type;
        $this->notificationableId = $notificationableId;
        $this->userId = $userId;
    }
}
