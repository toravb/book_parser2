<?php

namespace App\Models;

use App\Api\Interfaces\ReviewInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AudioBookReview extends Model implements ReviewInterface

{
    protected $fillable = [
        'user_id',
        'audio_book_id',
        'review_type_id',
        'title',
        'content'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function audioBook(): BelongsTo
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function reviewTypes(): BelongsTo
    {
        return $this->belongsTo(ReviewType::class, 'review_type_id', 'id');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function comments(): hasMany
    {
        return $this->hasMany(AudioBookReviewComment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable', 'like_type', 'like_id');
    }

    public function getReviews(int $id)
    {
        return $this
            ->with([
                'user:id,avatar,nickname',
                'reviewTypes'
            ])
            ->where('audio_book_id', $id)
            ->withCount(['likes', 'comments'])
            ->paginate(BookReview::PERPAGE);
    }
}
