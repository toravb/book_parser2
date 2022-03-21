<?php

namespace App\Models;

use App\Api\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookReviewComment extends Model implements CommentInterface
{
    public function bookReview(): BelongsTo
    {
        return $this->belongsTo(BookReview::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(BookReviewCommentLike::class);
    }

    public function getComments(int $typeId, int $paginate)
    {
        return $this
            ->where('book_review_id', $typeId)
            ->whereNull('parent_comment_id')
            ->select('id', 'book_review_id', 'user_id', 'content', 'updated_at')
            ->with('users:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }

    public function getCommentsOnComment(int $commentId, int $paginate)
    {
        return $this->where('parent_comment_id', $commentId)
            ->select('id', 'book_review_id', 'user_id', 'content', 'updated_at')
            ->with('users:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }
}
