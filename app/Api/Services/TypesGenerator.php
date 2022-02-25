<?php

namespace App\Api\Services;

use App\Api\Events\NewNotificationEvent;
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
        'audio_book' => 'App\\Api\\Models\\AudioBookLike',
        'book_comment' => 'App\\Api\\Models\\BookCommentLike'
    ];

    protected $likeModelTypes = [
        'book' => 'App\\Models\\Book',
        'audio_book' => 'App\\Api\\Models\\AudioBook',
        'book_comment' => 'App\\Models\\BookComment'
    ];

    protected $compilationsBookTypes = [
        QueryFilter::TYPE_BOOK => 'App\Models\Book',
        QueryFilter::TYPE_AUDIO_BOOK => 'App\Models\AudioBook',
    ];

    protected array $notificationableTypes = [
        NewNotificationEvent::LIKED_COMMENT => [
            'book_comment' => 'App\\Models\\BookComment'
        ],
        NewNotificationEvent::ANSWER_ON_COMMENT_AND_ALSO_COMMENTED => [
            'book' => 'App\\Models\\BookComment'
        ]
    ];

    protected array $notificationableHandleTypes = [
        NewNotificationEvent::LIKED_COMMENT => 'App\\Api\\Notifications\\LikedComment',
        NewNotificationEvent::ANSWER_ON_COMMENT_AND_ALSO_COMMENTED => 'App\\Api\\Notifications\\CommentsNotifications'
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

    protected $viewsTypes = [
        QueryFilter::TYPE_BOOK => 'App\Models\Book',
        QueryFilter::TYPE_AUDIO_BOOK => 'App\Models\AudioBook',
        'compilation' => 'App\\Models\\Compilation',
        'review' => 'App\\Models\\BookReview'
    ];

    protected array $searchableTypes = [
        'books' => 'App\Models\Book',
        'audio_books' => 'App\Models\AudioBook',
        'authors' => 'App\Models\Author',
        'compilations' => 'App\Models\Compilation',
        'series' => 'App\Models\Series'
    ];

    protected array $searchableRepositories = [
        'books' => 'App\Api\Repositories\BookSearchRepository',
        'audio_books' => 'App\Api\Repositories\AudioBookSearchRepository',
        'authors' => 'App\Api\Repositories\AuthorSearchRepository',
        'compilations' => 'App\Api\Repositories\CompilationSearchRepository',
        'series' => 'App\Api\Repositories\SeriesSearchRepository'
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

    public function getNotificationTypes(): array
    {
        return $this->notificationableTypes;
    }


    public function getNotificationHandleObjects(): array
    {
        return $this->notificationableHandleTypes;
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

    public function getViewsTypes(): array
    {
        return $this->viewsTypes;
    }

    public function getSearchableTypes(): array
    {
        return $this->searchableTypes;
    }

    public function getSearchableRepositories(): array
    {
        return $this->searchableRepositories;
    }
}
