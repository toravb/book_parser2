<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioBookComment extends Model
{

    protected $fillable = [
        'user_id',
        'audio_book_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audioBook()
    {
        return$this->belongsTo(AudioBook::class);
    }
}
