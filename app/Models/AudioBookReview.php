<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function comments(): hasMany
    {
        return $this->hasMany(AudioBookCommentReview::class);
    }
}
