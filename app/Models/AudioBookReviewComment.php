<?php

namespace App\Models;

use App\Api\Interfaces\CommentInterface;
use App\Api\Models\AudioBookCommentLike;
use App\Api\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AudioBookReviewComment extends Model implements CommentInterface
{

    protected $fillable = [
        'user_id',
        'audio_book_review_id',
        'content',
        'parent_comment_id'
    ];

    public function bookReview(): BelongsTo
    {
        return $this->belongsTo(AudioBookReview::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(
            AudioBookReviewCommentLike::class,
            'audio_review_comment_id',
            'id'
        );
    }

    public function userLike(): HasOne
    {
        return $this->hasOne(
            AudioBookReviewCommentLike::class,
            'audio_review_comment_id',
            'id'
        )
            ->where('user_id', auth('api')->id());
    }

    public function getBookObject()
    {
        return $this->audioBook;
    }

    public static function getNotificationComment(int $commentId)
    {
        return self::query()
            ->with('bookReview:id,title')
            ->findOrFail($commentId);
    }

    public function getComments(int $typeId, int $paginate)
    {
        return $this
            ->where('audio_book_review_id', $typeId)
            ->whereNull('parent_comment_id')
            ->select('id', 'audio_book_review_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,name,avatar,nickname')
            ->withCount('likes')
            ->withExists('userLike as is_liked')
            ->paginate($paginate);
    }

    public function getCommentsOnComment(int $commentId, int $paginate)
    {
        return $this->where('parent_comment_id', $commentId)
            ->select('id', 'audio_book_review_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,name,avatar,nickname')
            ->withCount('likes')
            ->withExists('userLike as is_liked')
            ->paginate($paginate);
    }
}
