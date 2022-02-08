<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioBookReview extends Model

{
    protected $fillable = [
        'user_id',
        'audio_book_id',
        'review_type_id',
        'title',
        'content'
    ];

    public function audioBook(): BelongsTo
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function reviewTypes()
    {
        return $this->belongsTo(ReviewType::class);
    }
}
