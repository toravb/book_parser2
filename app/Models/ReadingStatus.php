<?php

namespace App\Models;

use App\Api\Http\Requests\ReadingStatusStoreRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingStatus extends Model
{
    protected $fillable = ['user_id', 'book_id', 'page_number', 'reading_progress'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function show(int $bookId): ReadingStatus
    {
        return $this->where('book_id', $bookId)->where('user_id', auth('api')->id())->firstOrFail();
    }

    public function storeCurrentReadingStatus(int $bookId, int $pageNubmber, int $readingProgress): ReadingStatus
    {
        return $this->updateOrCreate(
            ['user_id' => auth('api')->id(), 'book_id' => $bookId],
            ['page_number' => $pageNubmber, 'reading_progress' => $readingProgress]
        );
    }

}
