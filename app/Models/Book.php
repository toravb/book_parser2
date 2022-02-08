<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Interfaces\BookInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model implements BookInterface
{
    use HasFactory;

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

    protected $fillable = [
        'title',
        'series_id',
        'year_id',
        'link',
        'params',
        'text',
        'donor_id'
    ];

    protected $hidden = ['pivot'];

    protected $appends = [
        'type'
    ];

    public static array $availableReadingStatuses = [
        self::WANT_READ,
        self::READING,
        self::HAD_READ
    ];

    public function getTypeAttribute()
    {

        return 'books';

    }

//    protected $morphClass = 'Book';

    public static function create($fields)
    {
        $book = new static();
        $book->fill($fields);
        $book->save();

        return $book;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function year(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }

    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id', 'id');
    }

    public function pageLinks()
    {
        return $this->hasMany(PageLink::class, 'book_id', 'id');
    }

    public function authors()
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

    public function publishers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
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

    public function pages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'book_id', 'id');
    }

    public function image(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Image::class, 'book_id', 'id');
    }

    public function images()
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

    public function bookGenres()
    {
        return $this->belongsToMany(BookGenre::class);
    }

    public function rates()
    {
        return $this->belongsToMany(User::class, 'rates');
    }

    public function bookComments()
    {
        return $this->hasMany(BookComment::class);
    }

    public function bookLikes()
    {
        return $this->hasMany(BookLike::class);
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function latestReview()
    {
        return $this->hasOne(Review::class)->latest();
    }

    public function latestQuote()
    {
        return $this->hasOne(Quote::class)->latest();
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function bookStatuses()
    {
        return $this->hasMany(BookUser::class);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('rates_avg', 'desc');
    }

    public function genres()
    {
        return $this->hasManyThrough(
            BookGenre::class,
            BookBookGenre::class,
            'book_id',
            'id',
            'id',
            'book_genre_id'
        );
    }

    public function anchors()
    {
        return $this->hasMany(BookAnchor::class, 'book_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'book_user');
    }

    public function compilations()
    {
        return $this->morphToMany(Compilation::class,
            'compilationable',
            'book_compilation',
            'compilationable_id',
            'compilation_id',
            'id',
            'id');
    }

    public function views(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookCompilation()
    {
        return $this->morphOne(BookCompilation::class, 'bookCompilationable');
    }

    public function comments()
    {
        return $this->hasMany(BookComment::class);
    }

    public function userRecommends()
    {
        return $this->hasMany(UsersRecommendation::class);
    }

    public function getBook(): Builder
    {
        return $this->with([
            'authors',
            'image',
            'bookGenres',
            'bookStatuses'
        ])
            ->select('id', 'title', 'year_id')
            ->withCount('rates')
            ->withAvg('rates as rates_avg', 'rates.rating');
    }


    public function currentReading($request)
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

    public function singleBook($bookId)
    {
        return $this->with([
            'authors',
            'image',
            'bookGenres',
            'year',
            'publishers',
            'comments',
            'reviews',
            'quotes'])
            ->where('id', $bookId)
            ->select('id', 'title', 'text')
            ->withCount(['rates', 'bookLikes', 'comments', 'reviews', 'quotes', 'views'])
            ->withAvg('rates as rates_avg', 'rates.rating')
            ->firstOrFail();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function chapters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Chapter::class);
    }


}

