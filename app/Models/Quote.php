<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Requests\QuoteIdExistsRequest;
use App\Api\Http\Requests\SaveQuotesRequest;
use App\Api\Http\Requests\ShowQuotesRequest;
use App\Api\Services\ApiAnswerService;
use http\Exception\BadMethodCallException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Api\Http\Requests\UpdateQuoteRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpFoundation\Response;
use TheSeer\Tokenizer\Exception;

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

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
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
                return $query->where('text', 'like', '%' . $request->search . '%');
            })
            ->when($request->myQuotes, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->with('page:id,page_number')
            ->get();
    }

    public function showInBook(QuoteIdExistsRequest $request)
    {
        return $this->with('page:id,page_number')->find($request->id);
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

    /**
     * @param int $bookId
     * @return LengthAwarePaginator
     */
    public function getQuotesForBookPage(int $bookId): LengthAwarePaginator
    {
        return $this
            ->select('id', 'user_id', 'book_id', 'text', 'page_id', 'updated_at')
            ->where('book_id', $bookId)
            ->with('user:id,avatar,nickname', 'page:id,page_number')
            ->withCount('likes', 'views')
            ->paginate(self::QUOTES_PER_PAGE);
    }

    /**
     * @param int $userId
     * @return Builder
     */
    public function showUserQuotes(int $userId): Builder
    {
        return $this->where('user_id', $userId)
            ->select('quotes.id', 'quotes.book_id', 'user_id', 'quotes.text', 'quotes.page_id')
            ->with([
                'book' => function ($query) {
                    $query->select('books.id', 'title')
                        ->withCount('rates')
                        ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
                        ->with([
                            'image:book_id,link',
                            'authors' => function ($query) {
                                $query->select('authors.id', 'author');
                            }
                        ]);
                },
                'page:id,page_number'
            ])->withCount('likes');
    }

    public function updateQuote(UpdateQuoteRequest $request)
    {
        try {
            $quoteForUpdate = $this->where('user_id', \auth()->id())->findOrFail($request->id);
        } catch (\Throwable $exception) {
            return ApiAnswerService::errorAnswer('Нет прав для редактирования', Response::HTTP_FORBIDDEN);
        }
        $quoteForUpdate->store(\auth()->id(), $request);

        return ApiAnswerService::successfulAnswerWithData($quoteForUpdate);
    }

    public static function getNotificationQuote(int $quoteId): Quote
    {
        return self::findOrFail($quoteId);
    }
}
