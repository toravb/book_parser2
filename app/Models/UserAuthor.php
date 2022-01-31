<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthor extends Model
{
    use HasFactory;

    protected $table = 'user_author';

    public function saveAuthor(int $userId, int $authorId)
    {
        $this->user_id = $userId;
        $this->author_id = $authorId;
        $this->save();
    }

    public function deleteAuthor(int $userId, int $authorId)
    {
        $this->where('user_id', $userId)
            ->where('author_id', $authorId)
            ->delete();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}
