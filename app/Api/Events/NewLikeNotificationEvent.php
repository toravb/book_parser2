<?php

namespace App\Api\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLikeNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $channel;
    public string $type;
    const TYPE =  'new_comment_like';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public array $to, public User  $sender, public $book, public $createdAt)
    {
        $this->type = self::TYPE;
        $this->channel = config('broadcasting.name_notification_channel');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channel);
    }
}
