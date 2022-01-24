<?php

namespace App\Models;

use App\Api\Http\Requests\SaveQuotesRequest;
use App\Api\Http\Requests\ShowQuotesRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

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

    public function showInBook(int $qouteId)
    {
        return $this->findOrFail($qouteId);

    }

    public function deleteQuote(int $userId, int $quoteId)
    {
        return $this->where('user_id', $userId)
            ->where('id', $quoteId)
            ->delete();
    }


}
