<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioBookCommentReview extends Model
{
    public function bookReview(): BelongsTo
    {
        return $this->belongsTo(AudioBookReview::class);
    }
}
