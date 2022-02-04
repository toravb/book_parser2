<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersRecommendation extends Model
{
    protected $table = 'users_recommended';

    protected $fillable = [
        'book_id',
        'audio_book_id',
        'content'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function audioBook(): BelongsTo
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function saveRecommend($request)
    {
        $field = $this->getFieldName($request->type);
        return $this->create([
            $field => $request->id,
            'content' => $request->content
        ]);
    }

    private function getFieldName($type)
    {
        return $type . '_id';
    }

}

