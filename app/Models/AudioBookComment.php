<?php

namespace App\Models;

use App\Api\Interfaces\CommentInterface;
use App\Api\Models\AudioBookCommentLike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class AudioBookComment extends Model implements CommentInterface
{
    protected $fillable = [
        'user_id',
        'audio_book_id',
        'content',
        'parent_comment_id'
    ];

    public static function getNotificationComment(int $commentId)
    {
        return self::with([
            'audioBook' => function ($query) {
                return $query->select('id', 'title');
            }
        ])
            ->findOrFail($commentId);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function audioBook(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AudioBookCommentLike::class);
    }

    public function userLike(): HasOne
    {
        return $this->hasOne(AudioBookCommentLike::class)->where('user_id', auth('api')->id());
    }

    public function getBookObject()
    {
        return $this->audioBook;
    }

    public function getComments(int $typeId, int $paginate)
    {
        return $this
            ->where('audio_book_id', $typeId)
            ->whereNull('parent_comment_id')
            ->select('id', 'audio_book_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,name,avatar,nickname')
            ->withCount('likes')
            ->withExists('userLike as is_liked')
            ->paginate($paginate);
    }

    public function getCommentsOnComment(int $commentId, int $paginate)
    {
        return $this->where('parent_comment_id', $commentId)
            ->select('id', 'audio_book_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,name,avatar,nickname')
            ->withCount('likes')
            ->withExists('userLike as is_liked')
            ->paginate($paginate);
    }
}

