<?php

namespace App\Models;

use App\Api\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookComment extends Model
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
        return $this->belongsTo(User::class);
    }

    public function books()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }
}
