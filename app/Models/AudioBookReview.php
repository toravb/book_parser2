<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioBookReview extends Model
{
    public function audioBook(): BelongsTo
    {
        return $this->belongsTo(AudioBook::class);
    }
}
