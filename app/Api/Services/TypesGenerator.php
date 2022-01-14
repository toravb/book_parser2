<?php

namespace App\Api\Services;

use App\Api\Http\Controllers\BookController;
use App\Api\Interfaces\Types;

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
        'audio_book' => 'App\\Api\\Models\\AudioBookLike'
    ];

    protected $likeModelTypes = [
        'book' => 'App\\Models\\Book',
        'audio_book' => 'App\\Api\\Models\\AudioBook'
    ];

    protected $compilationsBookTypes = [
        BookController::TYPE_BOOK => 'App\Models\Book',
        BookController::TYPE_AUDIO_BOOK => 'App\Models\AudioBook',
    ];


    public function getCommentTypes():array
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
}
