<?php

namespace App\Api\Services;

use App\Api\Events\NewNotificationEvent;
use App\Api\Filters\QueryFilter;
use App\Api\Http\Controllers\BookController;
use App\Api\Interfaces\Types;
use App\Models\AudioBook;
use App\Models\Book;

class TypesGenerator implements Types
{
    protected $commentTypes = [
        'post' => 'App\\PostsComment',
        'event' => 'App\\EventComment',
        'photo' => 'App\\MediaPhotoComment',
        'video' => 'App\\VideoComment',
    ];

    protected $likeTypes = [
        'book' => 'App\\Api\\Models\\BookLike',
        'audio_book' => 'App\\Api\\Models\\AudioBookLike',
        'comment' => 'App\\Api\\Models\\CommentLike'
    ];

    protected $likeModelTypes = [
        'book' => 'App\\Models\\Book',
        'audio_book' => 'App\\Api\\Models\\AudioBook'
    ];

    protected $compilationsBookTypes = [
        QueryFilter::TYPE_BOOK => 'App\Models\Book',
        QueryFilter::TYPE_AUDIO_BOOK => 'App\Models\AudioBook',
    ];

    protected $notificationableTypes = [
        NewNotificationEvent::LIKED_COMMENT => 'App\\Models\\Comment'
    ];


    public function getCommentTypes(): array
    {
        return $this->commentTypes;
    }

    public function getLikeTypes(): array
    {
        return $this->likeTypes;
    }

    public function getLikeModelTypes(): array
    {
        return $this->likeModelTypes;
    }

    public function getCompilationsBookTypes(): array
    {
        return $this->compilationsBookTypes;
    }

    public function getBookTypes(): array
    {
        return $this->compilationsBookTypes;
    }

    public function getNotificationTypes(): array
    {
        return $this->notificationableTypes;
    }
}
