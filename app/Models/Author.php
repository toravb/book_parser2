<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Interfaces\SearchModelInterface;
use App\Api\Traits\ElasticSearchTrait;
use App\Http\Requests\StoreAuthorRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Storage;

class Author extends Model implements SearchModelInterface
{
    use HasFactory, HasRelationships, ElasticSearchTrait;

    const NON_AUTHOR_COMPILATION_QUANTITY = 3;

    public $timestamps = false;

    protected $fillable = [
        'author',
        'about',
        'avatar'
    ];

    protected $hidden = ['pivot'];

    public static function create($fields): Author
    {
        $author = new static();
        $author->fill($fields);
        $author->save();

        return $author;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function saveFromRequest(StoreAuthorRequest $request)
    {
        $this->author = $request->author;
        if ($request->avatar) {
            if ($this->avatar) Storage::delete($this->avatar);

            $this->avatar = Storage::put('authors', $request->avatar);
        }
        $this->about = $request->about;
        $this->save();
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class,
            AuthorToBook::class,
            'author_id',
            'book_id',
            'id',
            'id',
            'books');
    }

    public function audioBooks(): BelongsToMany
    {
        return $this->belongsToMany(AudioBook::class,
            AuthorsToAudioBook::class,
            'author_id',
            'book_id',
            'id',
            'id',
            'audiobooks'
        );
    }

    public function series(): HasManyDeep
    {
        return $this->hasManyDeep(Series::class, ['author_to_books', Book::class],
            [
                null,
                null,
                'id',
                'id',
            ],
            [
                null,
                null,
                'book_id',
                'series_id'
            ]
        );
    }


    public function authorReviews(): HasManyThrough
    {
        return $this->hasManyThrough(
            BookReview::class,
            AuthorToBook::class,
            'author_id',
            'book_id',
            'authors.id',
            'book_id'
        );
    }

    public function authorQuotes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Quote::class,
            AuthorToBook::class,
            'author_id',
            'book_id',
            'authors.id',
            'book_id'
        );
    }

    public function similarAuthors(): HasManyThrough
    {
        return $this->hasManyThrough(
            Author::class,
            SimilarAuthors::class,
            'author_id_from',
            'id',
            'id',
            'author_id_to'
        );
    }

    public function audioAuthor(): BelongsTo
    {
        return $this->belongsTo(AudioAuthor::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_author');
    }

    public function withQuotesCount(int $author_id): ?Author
    {
        return $this->select(['id', 'author'])
            ->withCount('authorQuotes')->find($author_id);
    }

    public function reviewAuthorCount(int $authorId): ?Author
    {
        return $this->select('id', 'author')
            ->withCount('authorReviews')->find($authorId);
    }

    public function showOtherBooks($authorId): ?Author
    {
        return $this->select('id')
            ->with([
                'books' => function ($query) {
                    return $query
                        ->with([
                            'image' => function ($q) {
                                return $q->where('page_id', null)->select('book_id', 'link');
                            },
                            'authors:id,author'
                        ])
                        ->select('books.id', 'title')
                        ->withCount('views')
                        ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)');
                }
            ])->findOrFail($authorId);
    }

    public function showOtherAudioBooks($authorId)
    {
        return $this->select('id')
            ->with([
                'audioBooks' => function ($query) {
                    return $query
                        ->with(['image:book_id,link', 'authors:id,author'])
                        ->select('audio_books.id', 'title')
                        ->withCount('views')
                        ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)');
                }
            ])->findOrFail($authorId);
    }

    public function letterFiltering($letter)
    {
        return $this
            ->where('author', 'like', $letter . '%')
            ->select('id', 'author')
            ->orderBy('author')
            ->paginate(300);
    }

    public function toSearchArray(): array
    {
        return [
            'title' => $this->author
        ];
    }

    public function baseSearchQuery(): Builder
    {
        return $this->select('id', 'author', 'avatar')->withCount(['books', 'audioBooks']);
    }

    public function getElasticKey()
    {
        return $this->getKey();
    }

    public function authorPage(): ?Author
    {
        $authorPageData = $this
            ->with([
                'series' => function ($query) {
                    $query->with([
                        'books' => function ($query) {
                            return $query
                                ->select('id', 'title', 'series_id', 'active')
                                ->where('active', true)
                                ->withCount('views')
                                ->withAggregate('rates as rate_avg', 'Coalesce(Avg(rates.rating), 0)');
                        },
                        'books.genres:name',
                        'books.authors:author',
                        'books.image:book_id,link'
                    ])->distinct();
                },
                'books' => function ($query) {
                    return $query
                        ->select('books.id', 'books.title', 'books.active', 'books.series_id')
                        ->where('books.active', true)
                        ->with([
                            'genres:name',
                            'authors:author',
                            'image:book_id,link'
                        ])
                        ->withCount('views')
                        ->withAggregate('rates as rate_avg', 'Coalesce(Avg(rates.rating), 0)')
                        ->whereNull('series_id');
                },
                'audioBooks' => function ($query) {
                    return $query
                        ->select('audio_books.id', 'audio_books.active', 'audio_books.title', 'genre_id')
                        ->where('active', true)
                        ->with([
                            'genre:id,name',
                            'authors:author',
                            'image:book_id,link'
                        ])
                        ->withCount('views')
                        ->withAggregate('rates as rate_avg', 'Coalesce(Avg(rates.rating), 0)');
                },
            ])
            ->withCount(['authorReviews', 'authorQuotes', 'books', 'audioBooks'])
            ->find($this->id);

        $authorPageData->total_books = $authorPageData->books_count + $authorPageData->audio_books_count;

        $authorPageData->non_author_compilation =
            Compilation::select(['id', 'title', 'background'])
                ->whereHas('books.authors', function ($query) {
                    $query->where('authors.id', $this->id);
                })
                ->withCount('books')
                ->paginate(self::NON_AUTHOR_COMPILATION_QUANTITY, [], 'non-author-compilation');

        $authorPageData->similar_authors = $authorPageData
            ->similarAuthors()
            ->select('id', 'author', 'avatar')
            ->withCount('books')
            ->paginate(self::NON_AUTHOR_COMPILATION_QUANTITY, [], 'similar-authors');

        $authorPageData->in_favorite = $authorPageData->users()->exists();

        return $authorPageData;
    }
}
