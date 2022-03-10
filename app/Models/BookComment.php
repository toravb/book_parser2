<?php

namespace App\Models;

use App\Api\Http\Controllers\CommentController;
use App\Api\Interfaces\CommentInterface;
use App\Api\Models\BookCommentLike;
use App\Api\Models\CommentLike;
use App\Api\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookComment extends Model implements CommentInterface
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'content',
        'parent_comment_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function books()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }

    public function likes()
    {
        return $this->hasMany(BookCommentLike::class);
    }

    public static function getNotificationComment(int $commentId)
    {
        return self::with([
            'books' => function ($query) {
                return $query->select('id', 'title');
            }
        ])
            ->findOrFail($commentId);
    }

    public function getBookObject()
    {
        return $this->books;
    }

    public function getComments(int $bookId, int $paginate)
    {
        return $this
            ->where('book_id', $bookId)
            ->whereNull('parent_comment_id')
            ->select('id', 'book_id', 'user_id', 'content', 'updated_at')
            ->with('users:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }

    public function getCommentsOnComment(int $commentId, int $paginate)
    {
        return $this->where('parent_comment_id', $commentId)
            ->select('id', 'book_id', 'user_id', 'content', 'updated_at')
            ->with('users:id,avatar,nickname')
            ->withCount('likes')
            ->paginate($paginate);
    }

}
