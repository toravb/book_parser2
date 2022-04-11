<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\SaveQuotesRequest;
use App\Api\Http\Requests\ShowQuotesRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Quote extends Model
{
    use HasFactory;

    const SHOW_ALL = '1';
    const SORT_BY_BOOK_TITLE = '2';
    const SORT_BY_AUTHOR = '3';
    const QUOTES_PER_PAGE = 3;

    protected $appends = [
        'type'
    ];

    public function getTypeAttribute(): string
    {
        return $this->getRawOriginal['type'] ?? 'quotes';
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class, 'viewable_id', 'id')
            ->where('viewable_type', $this->getTypeAttribute());
    }

    public function likes(): HasMany
    {
        return $this->hasMany(QuoteLike::class);
    }

    public function store(int $userId, SaveQuotesRequest $request)
    {
        $this->user_id = $userId;
        $this->book_id = $request->book_id;
        $this->page_id = $request->page_id;
        $this->text = $request->text;
        $this->color = $request->color;
        $this->start_key = $request->start_key;
        $this->start_text_index = $request->start_text_index;
        $this->start_offset = $request->start_offset;
        $this->end_key = $request->end_key;
        $this->end_text_index = $request->end_text_index;
        $this->end_offset = $request->end_offset;

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

    public function getQuotesForBookPage(int $bookId)
    {
        return $this
            ->select('id', 'user_id', 'book_id', 'text', 'updated_at')
            ->where('book_id', $bookId)
            ->with('user:id,avatar,nickname')
            ->withCount('likes', 'views')
            ->paginate(self::QUOTES_PER_PAGE);
    }

    public function showUserQuotes(int $userId): Builder
    {
        return $this->where('user_id', $userId)
            ->select('quotes.id', 'quotes.book_id', 'user_id', 'quotes.text')
            ->with(['book' => function ($query) {
                $query->select('books.id', 'title')
                    ->withCount('rates')
                    ->withAvg('rates as rates_avg', 'rates.rating')
                    ->with(['image:book_id,link',
                        'authors' => function ($query) {
                            $query->select('authors.id', 'author');
                        }]);
            }])->withCount('likes');
    }
}
