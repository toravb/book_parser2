<?php

namespace App\Models;

use App\Api\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AudioBookReviewComment extends Model implements CommentInterface
{
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

    public function getComments(int $typeId, int $paginate)
    {
        return $this
            ->where('audio_book_review_id', $typeId)
            ->whereNull('parent_comment_id')
            ->select('id', 'audio_book_review_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }

    public function getCommentsOnComment(int $commentId, int $paginate)
    {
        return $this->where('parent_comment_id', $commentId)
            ->select('id', 'audio_book_review_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }
}
