<?php

namespace App\Models;

use App\Api\Filters\AudioBookFilter;
use App\Api\Interfaces\ReviewInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AudioBookReview extends Model implements ReviewInterface

{
    protected $fillable = [
        'user_id',
        'audio_book_id',
        'review_type_id',
        'title',
        'content'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function audioBook(): BelongsTo
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function reviewTypes(): BelongsTo
    {
        return $this->belongsTo(ReviewType::class, 'review_type_id', 'id');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function comments(): hasMany
    {
        return $this->hasMany(AudioBookReviewComment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(AudioBookReviewLike::class);
    }

    public function userLike(): HasOne
    {
        return $this->hasOne(AudioBookReviewLike::class)->where('user_id', auth('api')->id());
    }

    public function getReviews(int $id)
    {
        return $this
            ->with([
                'user:id,avatar,nickname',
                'reviewTypes'
            ])
            ->where('audio_book_id', $id)
            ->withExists('userLike as is_liked')
            ->withCount(['likes', 'comments'])
            ->paginate(BookReview::PER_PAGE);
    }

    public static function getNotificationReview(int $reviewId)
    {
        return self::with([
            'audioBook' => function ($query) {
                return $query->select('id', 'title');
            }
        ])
            ->findOrFail($reviewId);
    }

    public function getBookObject()
    {
        return $this->audioBook;
    }

    public function getUserReviews(int $userID, $request): Collection
    {
        return $this->where('user_id', $userID)
            ->whereHas('audioBook', function ($query) use ($request) {
                return $query->filter(new AudioBookFilter($request));
            })
            ->select([
                'id',
                'title',
                'content',
                'created_at',
                'audio_book_id'
            ])
            ->with([
                'audioBook:id,title',
                'audioBook.image:book_id,public_path as link',
                'audioBook.authors:id,author'
            ])
            ->withCount([
                'likes',
                'views',
                'comments'
            ])
            ->get();
    }
}
