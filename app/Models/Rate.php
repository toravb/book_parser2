<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;


    protected $fillable = [
        'user_id',
        'book_id',
        'audio_book_id',
        'rating'
    ];

    public function store($userId, $bookId, $rating)
    {
        $this->user_id = $userId;
        $this->book_id = $bookId;
        $this->rating = $rating;
        $this->save();
    }

    public function storeAudioBookRating($userId, $audioBookId, $rating)
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

    public function returnedRate(int $id)
    {
        return (new Book())->select('id')
            ->where('id', $id)
            ->withAvg('rates as rate_avg', 'rates.rating')
            ->withCount('rates')
            ->get();
    }

    public function returnedAudioRate(int $id)
    {
        return (new AudioBook())->select('id')
            ->where('id', $id)
            ->withAvg('rates as rate_avg', 'rates.rating')
            ->withCount('rates')
            ->get();
    }

}
