<?php

namespace App\Models;

use App\Http\Requests\BookmarkRequest;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Bookmark extends Model
{

    protected $fillable = ['book_id', 'user_id', 'page_id'];

    public function book(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class);
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


