<?php

namespace App\Models;

use App\Api\Interfaces\CommentInterface;
use App\Api\Models\AudioBookCommentLike;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class AudioBookComment extends Model implements CommentInterface
{

    protected $fillable = [
        'user_id',
        'audio_book_id',
        'content',
        'parent_comment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audioBook()
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function likes()
    {
        return $this->hasMany(AudioBookCommentLike::class);
    }

    public static function getNotificationComment(int $commentId)
    {
        return self::with([
            'audioBook' => function ($query) {
                return $query->select('id', 'title');
            }
        ])
            ->findOrFail($commentId);
    }

    public function getBookObject()
    {
        return $this->audioBook;
    }

    public function getComments(int $bookId, int $paginate)
    {
        return $this
            ->where('audio_book_id', $bookId)
            ->whereNull('parent_comment_id')
            ->select('id', 'audio_book_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }

    public function getCommentsOnComment(int $commentId, int $paginate)
    {
        return $this->where('parent_comment_id', $commentId)
            ->select('id', 'audio_book_id', 'user_id', 'content', 'updated_at')
            ->with('user:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }
}
