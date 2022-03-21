<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

}
