<?php

namespace App\Models;

use App\Api\Interfaces\CommentInterface;
use App\Api\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BookReviewComment extends Model implements CommentInterface
{
    protected $fillable = [
        'user_id',
        'book_review_id',
        'content',
        'parent_comment_id'
    ];

    public function bookReview(): BelongsTo
    {
        return $this->belongsTo(BookReview::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(BookReviewCommentLike::class);
    }

    public static function getNotificationComment(int $commentId)
    {
        return self::with([
            'bookReview' => function ($query) {
                return $query->select('id', 'title');
            }
        ])
            ->findOrFail($commentId);
    }

    public function getComments(int $typeId, int $paginate)
    {
        return $this
            ->where('book_review_id', $typeId)
            ->whereNull('parent_comment_id')
            ->select('id', 'book_review_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,name,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }

    public function getCommentsOnComment(int $commentId, int $paginate)
    {
        return $this->where('parent_comment_id', $commentId)
            ->select('id', 'book_review_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,name,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }
}
