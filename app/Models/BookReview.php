<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'review_type_id',
        'title',
        'content'
    ];



  /*  public static function create($fields)
    {
        $reviews = new static();
        $reviews->fill($fields);
        $reviews->save();

        return $reviews;
    }
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reviewTypes()
    {
        return $this->belongsTo(ReviewType::class);
    }
}