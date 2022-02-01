<?php

namespace App\Models;

use App\Http\Requests\StoreAudioBookRatingRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'audio_book_id',
        'rating'
    ];

    public function store(int $userId, int $bookId, float $rating)
    {
        $this->user_id = $userId;
        $this->book_id = $bookId;
        $this->rating = $rating;
        $this->save();
    }

    public function storeAudioBookRating(int $userId, int $audioBookId, float $rating)
    {
        $this->user_id = $userId;
        $this->audio_book_id = $audioBookId;
        $this->rating = $rating;
        $this->save();
    }


    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function audioBook(): BelongsTo
    {
        return $this->belongsTo(AudioBook::class);
    }
}
