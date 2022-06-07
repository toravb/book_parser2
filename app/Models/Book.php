<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Controllers\MainPageController;
use App\Api\Http\Requests\CurrentReadingRequest;
use App\Api\Interfaces\BookInterface;
use App\Api\Interfaces\SearchModelInterface;
use App\Api\Models\Notification;
use App\Api\Traits\ElasticSearchTrait;
use App\Http\Requests\Admin\StoreBookRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use LaravelIdea\Helper\App\Models\_IH_Book_C;
use LaravelIdea\Helper\App\Models\_IH_Book_QB;
use Storage;
use Str;

class Book extends Model implements BookInterface, SearchModelInterface
{
    use HasFactory, ElasticSearchTrait;

    const PER_PAGE_BLOCKS = 40;
    const PER_PAGE_LIST = 13;
    const SHOW_TYPE_BLOCK = 'block';
    const SHOW_TYPE_LIST = 'list';

    const SORT_BY_DATE = '1';
    const SORT_BY_RATING_LAST_YEAR = '3';
    const SORT_BY_ALPHABET = '6';
    const WANT_READ = '1';
    const READING = '2';
    const HAD_READ = '3';
    const ALL = '0';

    public static array $availableReadingStatuses = [
        self::WANT_READ,
        self::READING,
        self::HAD_READ,
        self::ALL

    ];
    protected $fillable = [
        'title',
        'series_id',
        'year_id',
        'link',
        'params',
        'text',
        'donor_id',
        'active'
    ];
    protected $hidden = ['pivot'];
    protected $appends = [
        'type'
    ];

    public static function create($fields)
    {
        $book = new static();
        $book->fill($fields);
        $book->save();

        return $book;
    }

    public function getTypeAttribute(): string
    {
        return $this->getRawOriginal('type') ?? 'books';
    }

    public function saveFromRequest(StoreBookRequest $request)
    {
        $this->title = $request->title;
        $this->text = $request->text ?? '';
        $this->active = (bool)$request->active;
        $this->year_id = $request->year_id;
        $this->meta_description = $request->meta_description;
        $this->meta_keywords = $request->meta_keywords;
        $this->alias_url = $request->alias_url ?? Str::slug($request->title);

        $this->links ?? $this->link = '';
        $this->params ?? $this->params = '{}';

        $this->save();

        if ($request->cover_image_remove and $this->image) {
            Storage::delete($this->image->link);
            $this->image->delete();
        }

        if ($request->cover_image) {
            if ($this->image) {
                $image = $this->image;
                Storage::delete($image->link);
            } else {
                $image = new Image();
            }

            $image->link = Storage::put('books-covers', $request->cover_image);

            $this->image()->save($image);
        }

        $this->authors()->sync($request->authors_ids);
        $this->genres()->sync($request->genres_id);
    }

    public function image(): HasOne
    {
        return $this->hasOne(Image::class)->whereNull('page_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(
            Author::class,
            AuthorToBook::class,
            'book_id',
            'author_id',
            'id',
            'id',
            'authors'
        );
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function scopeDataForAdminPanel($q)
    {
        return $q->select([
            'books.id',
            'title',
            'active',
            'year_id',
        ])->with([
            'genres:id,name',
            'authors:id,author',
            'image:id,book_id,link',
            'year:id,year'
        ]);
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class, 'series_id', 'id');
    }

    public function pageLinks(): HasMany
    {
        return $this->hasMany(PageLink::class, 'book_id', 'id');
    }

    public function publishers(): BelongsToMany
    {
        return $this->belongsToMany(
            Publisher::class,
            PublisherToBook::class,
            'book_id',
            'publisher_id',
            'id',
            'id',
            'publishers'
        );
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'book_id', 'id');
    }

    public function images(): HasManyThrough
    {
        return $this->hasManyThrough(
            Page::class,
            Image::class,
            'book_id',
            'page_id',
            'id',
            'id'
        );
    }

    public function bookGenres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function rates(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rates');
    }

    public function bookLikes(): HasMany
    {
        return $this->hasMany(BookLike::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BookReview::class);
    }

    public function latestReview(): HasOne
    {
        return $this->hasOne(BookReview::class)->latest();
    }

    public function latestQuote(): HasOne
    {
        return $this->hasOne(Quote::class)->latest();
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function bookStatuses(): HasMany
    {
        return $this->hasMany(BookUser::class);
    }

    public function readers(): HasMany
    {
        return $this->bookStatuses()->where('status', QueryFilter::SORT_BY_READERS_COUNT);
    }

    public function setReaders(Book $book)
    {
        $allBooks = $book
            ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)')
            ->get();
        foreach ($allBooks as $oneBook) {
            $oneBook->readers_count = $oneBook->readers()->count();
            $oneBook->rate_avg = $oneBook->rates_avg;
            $oneBook->reviews_count = $oneBook->reviews()->count();
            $oneBook->save();

        }
    }

    public function scopePopular($query)
    {
        return $query->orderBy('rates_avg', 'desc');
    }

    public function anchors(): HasMany
    {
        return $this->hasMany(BookAnchor::class, 'book_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_user');
    }

    public function userList(): HasOne
    {
        return $this->hasOne(BookUser::class)
            ->where('user_id', auth('api')->id());
    }

    public function isInFavorite()
    {
        return $this->hasMany(BookUser::class)->where('user_id', auth('api')->id());
    }

    public function compilations(): MorphToMany
    {
        return $this->morphToMany(Compilation::class,
            'compilationable',
            'book_compilation',
            'compilationable_id',
            'compilation_id',
            'id',
            'id');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookCompilation(): MorphOne
    {
        return $this->morphOne(BookCompilation::class, 'bookCompilationable');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BookComment::class);
    }

    public function userRecommends(): HasMany
    {
        return $this->hasMany(UsersRecommendation::class);
    }

    public function getBook(): Builder
    {
        return $this->with([
            'authors:id,author',
            'image:id,link,book_id',
            'bookGenres:id,name',
        ])
            ->select('id', 'title', 'year_id')
            ->withCount(['rates', 'views'])
            ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)')
            ->withExists('userList as in_favorite');
    }


    public function currentReading(CurrentReadingRequest $request, int $pageNumber): Model|\Illuminate\Database\Eloquent\Collection|array|Builder|Book|_IH_Book_C|_IH_Book_QB|null
    {
        return $this->with([
            'authors:id,author',
            'pages' => function ($query) use ($pageNumber) {
                return $query
                    ->select('id', 'book_id', 'content', 'page_number')
                    ->where('page_number', $pageNumber);
            },
            'chapters' => function ($query) use ($pageNumber) {
                return $query
                    ->addSelect('chapters.*', 'page_id', 'chapters.book_id', 'title', 'pages.id', 'pages.page_number')
                    ->join('pages', 'chapters.page_id', '=', 'pages.id')
                    ->where('pages.page_number', '<=', $pageNumber)
                    ->orderBy('pages.page_number', 'desc')
                    ->addSelect('chapters.id')
                    ->first();
            }
        ])
            ->select([
                'id',
                'title'
            ])
            ->withCount('pages')
            ->findOrFail($request->id);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function singleBook($bookId): Model|Builder|Book|_IH_Book_QB
    {
        return $this->with([
            'authors:id,author',
            'image:book_id,link',
            'bookGenres:name',
            'year',
            'publishers:publisher', 'series'])
            ->where('id', $bookId)
            ->select('id',
                'title',
                'text',
                'year_id',
                'series_id',
                'params->translator as translator',
            )
            ->withCount(['rates', 'bookLikes', 'comments', 'reviews', 'quotes', 'views'])
            ->withAvg('rates as rates_avg', 'rates.rating')
            ->firstOrFail();
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function chaptersWithPages(): HasMany
    {
        return $this->hasMany(Chapter::class)->with('page:id,page_number');
    }

    public function chapterForReadingProgress()
    {
        return $this->hasOne(Chapter::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }

    public function getBookForLetterFilter(): Builder
    {
        return $this
            ->with([
                'authors:id,author'
            ])
            ->select(['id', 'title'])
            ->withCount('rates')
            ->withAggregate('rates as rates_avg', 'Coalesce ( avg( rates.rating), 0)');
    }

    public function hotDailyUpdates(): Collection
    {
        return $this
            ->select(['id', 'title', 'active', 'created_at'])
            ->where('active', true)
            ->where('created_at', '>', Carbon::now()->subDays(MainPageController::PERIOD_FOR_HOT_DAILY_UPDATES))
            ->with(['authors' => function ($query) {
                $query->select('author');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function (Book $book) {
                return Carbon::parse($book->created_at)->format('d-m-Y');
            });
    }

    public function getBooksForMainPageFilter(): Builder
    {
        return $this
            ->select(['id', 'title', 'active'])
            ->where('active', true)
            ->with([
                'genres:name',
                'authors:author',
                'image:book_id,link'
            ])
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->withCount('views');
    }

    public function noveltiesBooks(): Builder
    {
        return $this
            ->select('books.id', 'books.title', 'books.year_id')
            ->where('active', true)
            ->with([
                'genres:id,name',
                'authors:author',
                'image:book_id,link',
                'year:id,year'
            ])
            ->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->join('years', 'years.id', '=', 'books.year_id');
    }

    public function updateBook($fields): bool
    {
        return $this->fill($fields)->update();
    }

    public function baseSearchQuery(): Builder
    {
        return $this->getBook();
    }

    public function getElasticKey()
    {
        return $this->getKey();
    }

    public function storeBooksByAdmin(string $title, string $description, int $status, string $link)
    {
        $book = $this->create([
            'title' => $title,
            'text' => $description,
            'active' => $status,
            'link' => $link,
            'params' => '{}'
        ]);

        return $book->id;
    }

    public function booksWithQuotesForAuthorPage(int $authorId): LengthAwarePaginator
    {
        return $this->select(['books.id', 'title'])
            ->whereHas('quotes')
            ->whereHas('authors', function ($query) use ($authorId) {
                return $query->where('authors.id', $authorId);
            })
            ->with([
                'authors:id,author',
                'image:book_id,link',
                'latestQuote:id,user_id,book_id,text,created_at',
                'latestQuote.user:id,name,avatar',
            ])
            ->withAggregate('rates as rates_avg', 'coalesce( avg( rates.rating), 0)')
            ->withCount('views')
            ->paginate(8);
    }

    public function latestBookReviewWithUser(int $authorId): LengthAwarePaginator
    {
        return $this->select(['books.id', 'title'])
            ->whereHas('authors', function ($query) use ($authorId) {
                $query->where('authors.id', $authorId);
            })
            ->whereHas('reviews')
            ->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating),0)')
            ->with([
                'authors:id,author',
                'image:book_id,link',
                'latestReview:id,user_id,book_id,content,created_at',
                'latestReview.user:id,name,avatar',
                'latestReview.userBookRate:user_id,book_id,rating'
            ])
            ->paginate(8);
    }

    public function getSimilarBooks($genreId)
    {
        return Book::getBook()
            ->limit(4)
            ->whereHas('bookGenres', function ($q) use ($genreId) {
                $q->where('id', $genreId);
            })
            ->get();

    }
}
