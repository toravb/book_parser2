<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Controllers\MainPageController;
use App\Api\Interfaces\BookInterface;
use App\Api\Interfaces\SearchModelInterface;
use App\Api\Models\Notification;
use App\Api\Traits\ElasticSearchTrait;
use App\Http\Requests\Admin\StoreBookRequest;
use Carbon\Carbon;
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
    public static array $availableReadingStatuses = [
        self::WANT_READ,
        self::READING,
        self::HAD_READ
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
        return $this->hasOne(Review::class)->latest();
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
            'authors',
            'image',
            'bookGenres',
            'userList',
        ])
            ->select('id', 'title', 'year_id')
            ->withCount(['rates', 'views'])
            ->withAvg('rates as rates_avg', 'rates.rating');
    }


    public function currentReading($request): Model|\Illuminate\Database\Eloquent\Collection|array|Builder|Book|\LaravelIdea\Helper\App\Models\_IH_Book_C|\LaravelIdea\Helper\App\Models\_IH_Book_QB|null
    {
        $number = $request->pageNumber ? $request->pageNumber : 1;
        return $this->with([
            'authors',
            'pages' => function ($query) use ($number) {
                return $query->where('page_number', $number);
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
            'publishers:publisher'])
            ->where('id', $bookId)
            ->select('id', 'title', 'text', 'year_id')
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

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }

    public function getBookForLetterFilter(): Builder
    {
        return $this
            ->with(['authors' => function ($query) {
                return $query->select('author');
            }])
            ->select(['id', 'title'])
            ->withCount('rates')
            ->withAvg('rates as rates_avg', 'rates.rating');
    }

    public function hotDailyUpdates(): Collection
    {
        return $this
            ->select(['id', 'title', 'created_at'])
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
            ->select(['id', 'title'])
            ->with([
                'genres:name',
                'authors:author',
                'image:book_id,link'
            ])
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->withCount('views');
    }

    public function noveltiesBooks()
    {
        return $this
            ->select('books.id', 'books.title', 'books.year_id')
            ->with([
                'genres:name',
                'authors:author',
                'image:book_id,link',
                'year:id,year'
            ])
            ->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->join('years', 'years.id', '=', 'books.year_id');
    }

    public function updateBook($fields)
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
}
