<?php

namespace App\Models;

use App\Api\Http\Controllers\BookController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $table = 'book_user';

    protected $fillable = [
        'user_id',
        'book_id',
        'status'
    ];

    public function deleteBook(int $userId, int $bookId)
    {

        $this->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();
    }

    public function changeCreateStatus(int $userId, int $bookId, int $status)
    {
        $this->user_id = $userId;
        $this->book_id = $bookId;
        $this->status = $status;

        if ($this->userBook($userId, $bookId)->exists()) {
            $record = $this->userBook($userId, $bookId)->first(['created_at', 'updated_at', 'status']);

            $this->created_at = $record->created_at;
            $this->updated_at = $record->updated_at;

            if ($record->status !== $this->status) {
                $this->updated_at = Carbon::now();
                $this->userBook($userId, $bookId)->update(['status' => $this->status, 'updated_at' => $this->updated_at]);
            }
        } else {
            $this->save();
        }
    }


    public function scopeReading($query)
    {
        return $query->where('status', Book::SORT_BY_READERS_COUNT);
    }

    public function scopeUserBook($q, int $userId, int $bookId)
    {
        return $q->where('user_id', $userId)->where('book_id', $bookId);
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
