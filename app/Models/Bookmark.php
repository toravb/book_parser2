<?php

namespace App\Models;

use App\Http\Requests\BookmarkRequest;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bookmark extends Model
{
    protected $fillable = ['book_id', 'user_id', 'page_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public static function addGetBookmark(BookmarkRequest $request): Bookmark
    {
        return Bookmark::firstOrCreate([
            'book_id' => $request->book_id,
            'user_id' => \auth()->id(),
            'page_id' => $request->page_id,
        ]);
    }

}


