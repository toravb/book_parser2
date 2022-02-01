<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class AudioBookUser extends Model
{
    protected $table = 'audio_book_user';

    public function audioBook()
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeListening($query)
    {
        return $query->where('status', QueryFilter::SORT_BY_LISTENERS);
    }
}

