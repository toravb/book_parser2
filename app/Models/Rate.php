<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    public function store(int $userId, int $bookId, float $rating)
    {
        $this->user_id = $userId;
        $this->book_id = $bookId;
        $this->rating = $rating;
        $this->save();
    }


    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
