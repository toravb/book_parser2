<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Interfaces\SearchModelInterface;
use App\Api\Traits\ElasticSearchTrait;
use App\Http\Requests\StoreAuthorRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

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

    public static function create($fields)
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
        if($request->avatar) {
            if($this->avatar) \Storage::delete($this->avatar);

            $this->avatar = \Storage::put('authors', $request->avatar);
        }
        $this->about = $request->about;
        $this->save();
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class,
            AuthorToBook::class,
            'author_id',
            'book_id',
            'id',
            'id',
            'books');
    }

    public function audioBooks()
    {
        return $this->belongsToMany(AudioBook::class,
            AuthorsToAudioBook::class,
            'author_id',
            'book_id',
            'id',
            'id',
            'audiobooks');
    }

    public function series(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
    {
        return $this->hasManyDeep(Series::class, [AuthorToBook::class],
            [
                'author_id',
                'book_id',
                'id',
                'id',
            ]);
    }


    public function authorReviews(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
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

    public function authorQuotes()
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

    public function similarAuthors()
    {
        return $this->hasMany(
            SimilarAuthors::class,
            'author_id_from',
            'id');
    }

    public function audioAuthor()
    {
        return $this->belongsTo(AudioAuthor::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_author');
    }

    public function quotes(int $author_id): Author|null
    {
        $authorWithBooks = self::select(['id', 'author'])
            ->withCount('authorQuotes')
            ->with(['books' => function ($query) {
                $query->select(['books.id', 'title', 'link'])
                    ->withCount(['views', 'rates'])
                    ->whereHas('quotes')
                    ->with(['latestQuote' => function ($query) {
                        $query->select(['id', 'user_id', 'book_id', 'content', 'created_at'])
                            ->with(['user' => function ($query) {
                                $query->select(['id', 'nickname', 'avatar', 'created_at']);
                            }]);
                    }]);
            }])
            ->find($author_id);

        return $authorWithBooks;
    }

    public function reviews(int $author_id): Author|null
    {
        $authorWithBooks = self::select(['authors.id', 'author'])
            ->withCount('authorReviews')
            ->with(['books' => function ($query) {
                $query->select(['books.id', 'title', 'link'])
                    ->withCount(['views', 'rates'])
                    ->whereHas('reviews')
                    ->with(['latestReview' => function ($query) {
                        $query->select([
                            'reviews.user_id',
                            'reviews.book_id',
                            'reviews.content',
                            'reviews.created_at',
                            'rates.rating as review_rating',
                            'rates.book_id',
                            'rates.user_id',
                        ]);
                        $query->with(['user']);
                        $query->leftJoin('rates', function ($join) {
                            $join->on('rates.book_id', '=', 'reviews.book_id');
                            $join->on('rates.user_id', '=', 'reviews.user_id');
                        });
                    }]);
            }])
            ->find($author_id);

        return $authorWithBooks;
    }

    public function showOtherBooks($authorId)
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
