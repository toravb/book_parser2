<?php

namespace App\Models;

use App\Api\Http\Controllers\BookController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
    use HasFactory;

    protected $table = 'book_user';

    public function saveBook(int $userId, int $bookId, int $status)
    {

        $this->user_id = $userId;
        $this->book_id = $bookId;
        $this->status = $status;
        $this->save();
    }
    public function deleteBook(int $userId, int $bookId)
    {

        $this->where('user_id', $userId)
        ->where('book_id', $bookId)
        ->delete();
    }

    public function changeStatus(int $userId, int $bookId, int $status)
    {
        $this->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->update(['status' => $status]);

    }




    public function scopeReading($query)
{
    return $query->where('status', BookController::SORT_BY_READERS_COUNT);
}

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }


}
