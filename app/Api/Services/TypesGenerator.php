<?php

namespace App\Api\Services;

use App\Api\Filters\QueryFilter;
use App\Api\Interfaces\Types;

class TypesGenerator implements Types
{
    protected $commentTypes = [
        'book' => 'App\\Models\\BookComment',
        'audio_book' => 'App\\Models\\AudioBookComment'
    ];

    protected $commentModelTypes = [
        'book' => 'App\\Models\\Book',
        'audio_book' => 'App\\Models\\AudioBook'
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
        QueryFilter::TYPE_BOOK => 'App\Models\Book',
        QueryFilter::TYPE_AUDIO_BOOK => 'App\Models\AudioBook',
    ];

    protected $reviewTypes = [
        'book' => 'App\\Models\\BookReview',
        'audio_book' => 'App\\Models\\AudioBookReview'
    ];
    protected $reviewModelTypes = [
        'book' => 'App\\Models\\Book',
        'audio_book' => 'App\\Models\\AudioBook'
    ];

    protected $recommendTypes = [
        'book' => 'App\\Models\\UserRecommendation',
        'audio_book' => 'App\\Models\\UserRecommendation'
    ];

    protected $recommendModelTypes = [
        'book' => 'App\\Models\\Book',
        'audio_book' => 'App\\Models\\AudioBook'
    ];


    public function getCommentTypes(): array
    {
        return $this->commentTypes;
    }

    public function getCommentModelTypes(): array
    {
        return $this->commentModelTypes;
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
    public function getReviewTypes(): array
    {
        return $this->reviewTypes;
    }
    public function getReviewModelTypes(): array
    {
        return $this->reviewModelTypes;
    }
    public function getRecommendTypes(): array
    {
        return $this->recommendTypes;
    }
    public function getRecommendModelTypes(): array
    {
        return $this->recommendModelTypes;
    }

}
