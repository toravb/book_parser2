<?php

namespace App\Models;

use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\SaveQuotesRequest;
use App\Api\Http\Requests\ShowQuotesRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    const SHOW_BY_BOOK_AND_AUTHOR = '1';
    const SHOW_BY_BOOK = '2';
    const SHOW_BY_AUTHOR = '3';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function store(int $userId, SaveQuotesRequest $request)
    {
        $this->user_id = $userId;
        $this->book_id = $request->bookId;
        $this->page_id = $request->pageId;
        $this->content = $request->text;
        $this->color = $request->color;
        $this->position = $request->position;
        $this->save();
    }

    public function showAll(int $userId, ShowQuotesRequest $request)
    {
        return $this->where('book_id', $request->bookId)
            ->when($request->search, function ($query) use ($request) {
                return $query->where('content', 'like', '%' . $request->search . '%');
            })
            ->when($request->myQuotes, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->get();
    }

    public function showInBook(GetIdRequest $request)
    {
        return $this->find($request->id);
    }

    /**
     * @param int $userId
     * @param int $quoteId
     * @return int
     */
    public function deleteQuote(int $userId, int $quoteId): int
    {
        return $this->where('user_id', $userId)
            ->where('id', $quoteId)
            ->delete();
    }
    public function showUsers(int $userId, ShowQuotesRequest $request)
    {

//        return $this->where('user_id', $userId)
//            ->when($request->showBy===self::SHOW_BY_BOOK, function ($query) use ($request) {
//                return $query->orderBy('book_id');
//            })
//            ->when($request->showBy===self::SHOW_BY_AUTHOR, function ($query) use ($request) {
//                return $query-> whereHasThrue('book', function (Builder $query) {
//                    $query->where('a', 'like', 'code%');
//                })->get();)->   orderBy('');
//            })
//            ->get();
    }

}
