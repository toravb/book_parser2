<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioBookReviewComment extends Model
{
    public function bookReview(): BelongsTo
    {
        return $this->belongsTo(AudioBookReview::class);
    }
}
