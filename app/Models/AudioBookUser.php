<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AudioBookUser extends Model
{
    protected $table = 'audio_book_user';

    public $guarded = [];

    public function audioBook()
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeListening($query)
    {
        return $query->where('status', QueryFilter::SORT_BY_LISTENERS);
    }

    public function createChangeStatus(int $userId, int $bookId, int $status)
    {
        $record = $this->userAudioBook($userId, $bookId)->first();
        if ($record) {
            $this->fill($record->toArray());

            if ($record->status !== $status) {
                $this->status = $status;
                $this->updated_at = Carbon::now();

                $this->userAudioBook($userId, $bookId)->update($this->only(['status', 'updated_at']));
            }
        } else {
            $this->fill([
                'user_id' => $userId,
                'audio_book_id' => $bookId,
                'status' => $status,
            ]);
            $this->save();
        }
    }

    public function deleteFromUsersList(int $userId, int $audioBookId)
    {
        return $this
            ->where('user_id', $userId)
            ->where('audio_book_id', $audioBookId)
            ->delete();
    }

    public function scopeUserAudioBook($query, int $userId, int $bookId)
    {
        return $query->where('user_id', $userId)->where('audio_book_id', $bookId);
    }

}

