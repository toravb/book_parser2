<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
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

    protected $guarded = [];

    public function deleteBook(int $userId, int $bookId)
    {

        $this->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();
    }

    public function changeCreateStatus(int $userId, int $bookId, int $status)
    {
        $record = $this->userBook($userId, $bookId)->first();

        if ($record) {
            $this->fill($record->toArray());

            if ($record->status !== $status) {
                $this->status = $status;
                $this->updated_at = Carbon::now();

                $this->userBook($userId, $bookId)->update($this->only(['status', 'updated_at']));
            }
        } else {
            $this->fill([
                'user_id' => $userId,
                'book_id' => $bookId,
                'status' => $status,
            ]);

            $this->save();
        }
    }


//    public function scopeReading($query)
//    {
//        return $query->where('status', QueryFilter::SORT_BY_READERS_COUNT);
//    }

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
