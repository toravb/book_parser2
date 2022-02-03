<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewType extends Model
{
    public function books()
    {
        return $this->hasMany(BookReview::class);
    }

    public function audioBooks()
    {
        return $this->hasMany(AudioBookReview::class);
    }
}
