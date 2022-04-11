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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Storage;

class Author extends Model implements SearchModelInterface
{
    use HasFactory, HasRelationships, ElasticSearchTrait;

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
        return $this->hasManyDeep(Series::class, [AuthorToBook::class],
            [
                'author_id',
                'book_id',
                'id',
                'id',
            ]);
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

    public function similarAuthors(): HasMany
    {
        return $this->hasMany(
            SimilarAuthors::class,
            'author_id_from',
            'id');
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
                            'authors'
                        ])
                        ->select('books.id', 'title')
                        ->withCount('views')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                }
            ])->findOrFail($authorId);
    }

    public function showOtherAudioBooks($authorId)
    {
        return $this->select('id')
            ->with([
                'audioBooks' => function ($query) {
                    return $query
                        ->with(['images', 'authors'])
                        ->select('audio_books.id', 'title')
                        ->withCount('views')
                        ->withAvg('rates as rates_avg', 'rates.rating');
                }
            ])->findOrFail($authorId);
    }

    public function letterFiltering($letter)
    {
        return $this
            ->where('author', 'like', $letter . '%')
            ->select('id', 'author')
            ->orderBy('author')
            ->get();
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
}
