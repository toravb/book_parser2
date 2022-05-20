<?php

namespace App\Models;

use App\Http\Requests\BookmarkRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    protected $fillable = ['book_id', 'user_id', 'page_id'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'book_id', 'book_id');
    }

    public function addGetBookmark(BookmarkRequest $request): Bookmark
    {
        $userBookmark = Bookmark::firstOrCreate([
            'book_id' => $request->book_id,
            'user_id' => \auth()->id(),
            'page_id' => $request->page_id,
        ]);

        $page = $userBookmark
            ->page()
            ->select(['id', 'page_number'])
            ->get();

        $userBookmark->chapter = $userBookmark
            ->chapter()
            ->addSelect('chapters.*', 'page_id', 'chapters.book_id', 'title', 'pages.id', 'pages.page_number')
            ->join('pages', 'chapters.page_id', '=', 'pages.id')
            ->where('pages.page_number', '<=', $page[0]['page_number'])
            ->orderBy('pages.page_number', 'desc')
            ->addSelect('chapters.id')
            ->first();

        $userBookmark->page = $page;

        return $userBookmark;
    }
}
