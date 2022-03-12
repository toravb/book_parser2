<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class AudioBookComment extends Model
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
}
