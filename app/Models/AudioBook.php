<?php

namespace App\Models;

use App\Api\Filters\AudioBookFilter;
use App\Api\Filters\QueryFilter;
use App\Api\Interfaces\BookInterface;
use App\Api\Interfaces\SearchModelInterface;
use App\Api\Traits\ElasticSearchTrait;
use App\Http\Requests\ShowAudioBooksUserHasRequest;
use App\Http\Requests\StoreAudioBookRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Pagination\LengthAwarePaginator;

class AudioBook extends Model implements BookInterface, SearchModelInterface
{
    use HasFactory, ElasticSearchTrait;

    const WANT_LISTEN = '1';
    const LISTENING = '2';
    const HAD_LISTEN = '3';
    const MAIN_PAGE_PAGINATE = 6;
    const ALL = '0';

    public static array $availableListeningStatuses = [
        self::WANT_LISTEN,
        self::LISTENING,
        self::HAD_LISTEN,
        self::ALL
    ];
    protected $fillable = [
        'title',
        'description',
        'params',
        'series_id',
        'link_id',
        'litres',

        'listeners_count',
        'rate_avg',
        'reviews_count',
    ];
    protected $hidden = ['pivot'];
    protected $appends = [
        'type'
    ];

    public function getTypeAttribute(): string
    {
        return $this->getRawOriginal['type'] ?? 'audioBooks';
    }

    public function saveFromRequest(StoreAudioBookRequest $request)
    {
        $this->title = $request->title;
        $this->description = $request->description;
        $this->year_id = $request->year_id;
        $this->genre_id = $request->genre_id;
        $this->meta_description = $request->meta_description;
        $this->meta_keywords = $request->meta_keywords;
        $this->alias_url = $request->alias_url ?? \Str::slug($request->title);
        $this->active = $request->active;

        if ($request->cover_image_remove and $this->image) {
            $this->image->delete();
        }

        if ($request->cover_image) {
            if ($this->image) {
                $image = $this->image;
                $image->deleteImageFile();
            } else {
                $image = new AudioImage();
            }

            $image->saveFromUploadedFile($request->cover_image);

            $this->image()->save($image);
        }

        $this->save();

        $this->authors()->sync($request->authors_ids);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, AuthorsToAudioBook::class, 'book_id');
    }

    public function images()
    {
        return $this->hasMany(
            AudioImage::class,
            'book_id',
            'id',
        );
    }

    public function image()
    {
        return $this->hasOne(
            AudioImage::class,
            'book_id',
            'id',
        );
    }

    public function audiobook()
    {
        return $this->hasOne(
            AudioAudiobook::class,
            'book_id',
            'id',
        );
    }

    public function audiobooks(): HasMany
    {
        return $this->hasMany(
            AudioAudiobook::class,
            'book_id',
            'id',
        );
    }

    public function genre(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function series()
    {
        return $this->belongsTo(
            AudioSeries::class,
            'series_id',
            'id'
        );
    }

    public function actors()
    {
        return $this->hasManyThrough(
            AudioReader::class,
            AudioReadersToBook::class,
            'book_id',
            'id',
            'id',
            'reader_id'
        );
    }

    public function compilations()
    {
        return $this->MorphToMany(Compilation::class,
            'compilationable',
            'book_compilation',
            'compilationable_id',
            'compilation_id',
            'id',
            'id');
    }

    public function bookCompilation()
    {
        return $this->morphOne(
            BookCompilation::class,
            'bookCompilationable',
            'compilationable_type',
            'compilationable_id'
        );
    }

    public function link()
    {
        return $this->belongsTo(AudioBooksLink::class, 'link_id', 'id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function rates(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rates');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function year()
    {
        return $this->hasOne(Year::class, 'id', 'year_id');
    }

    public function audioBookStatuses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AudioBookUser::class, 'audio_book_id', 'id');
    }

    public function setListeners(AudioBook $audioBook)
    {
        $allAudioBooks = $audioBook
            ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)')
            ->get();
        foreach ($allAudioBooks as $oneAudioBook) {
            $oneAudioBook->listeners_count = $oneAudioBook->audioBookStatuses()->count();
            $oneAudioBook->rate_avg = $oneAudioBook->rates_avg;
            $oneAudioBook->reviews_count = $oneAudioBook->reviews()->count();
            $oneAudioBook->save();
        }
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AudioBookReview::class);
    }

    public function comments()
    {
        return $this->hasMany(AudioBookComment::class);
    }

    public function usersRecommend()
    {
        return $this->hasMany(UsersRecommendation::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(
            AudioAudiobook::class,
            'book_id',
            'id'
        );
    }

    public function userList(): HasOne
    {
        return $this->hasOne(AudioBookUser::class)
            ->where('user_id', auth('api')->id());
    }

    public function showAudioBookDetails($bookId)
    {
        return $this->with([
            'authors',
            'image',
            'genre',
            'actors',
            'series',
            'year',
            'link',
        ])
            ->where('id', $bookId)
            ->select('id',
                'title',
                'description',
                'year_id',
                'series_id',
                'link_id',
                'genre_id',
                'params->translator as translator',
            )
            ->withCount(['views', 'audioBookStatuses as listeners_count', 'rates', 'reviews'])
            ->withAvg('rates as rates_avg', 'rates.rating')
            ->withAggregate('chapters as total_duration', 'Coalesce( sum( audio_audiobooks.duration), 0)')
            ->firstOrFail();
    }

    public function getBookForLetterFilter(): Builder
    {
        return $this
            ->with(['authors:id,author'])
            ->select(['id', 'title'])
            ->withCount('rates')
            ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)');
    }

    public function noveltiesBooks(): Builder
    {
        return $this
            ->select('id', 'title', 'year_id', 'genre_id')
            ->with([
                'genre:id,name',
                'authors:id,author',
                'image:book_id,link',
                'year:id,year'
            ])
            ->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->when($this->avg('year_id') > 0, function ($q) {
                $q->join('years', 'years.id', '=', 'year_id');
            });
    }

    public function baseSearchQuery(): Builder
    {
        return $this->getBook();
    }

    public function getBook(): Builder
    {
        return $this->with([
            'authors:id,author',
            'image:book_id,link',
            'genre:id,name',
        ])
            ->select('id', 'title', 'year_id', 'genre_id')
            ->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating), 0)');
    }

    public function getElasticKey()
    {
        return $this->getKey();
    }

    public function getForAdmin()
    {
        return $this
            ->select(
                'id',
                'active',
                'title',
                'year_id',
                'genre_id'
            )
            ->with([
                'genre:id,name',
                'authors:id,author',
                'year:id,year'
            ]);
    }

    public function storeAudioBooksByAdmin(
        int    $status,
        string $title,
        string $description,
//        int $genre,
//        int $series,
//        int $yearId
    )
    {
        $book = $this->create([
            'active' => $status,
            'title' => $title,
            'description' => $description,
//            'genre_id' => $genre,
//            'series_id' => $series,
//            'year_id' => $yearId
        ]);

        return $book->id;
    }

    public static function create($fields)
    {
        $book = new static();
        $book->fill($fields);
        $book->save();

        return $book;
    }

    public function audioBookChapters(): AudioBook
    {
        return $this
            ->where('id', $this->id)
            ->with('chapters:id,book_id,title,link,extension,file_size,duration,public_path,index')
            ->withAggregate('chapters as total_duration', 'Coalesce( sum( audio_audiobooks.duration), 0)')
            ->firstOrFail(['id', 'title']);
    }

    public function getSimilarAudioBooks()
    {
        return AudioBook::getBook()
            ->limit(4)
            ->where('genre_id', $this->genre_id)
            ->get();

    }

    public function getAudioBooksForMainPageComp(): Builder
    {
        return AudioBook::whereHas('compilations', function (Builder $query) {
            return $query->where('location', Compilation::NO_TIME_FOR_READ_LOCATION);
        })
            ->select([
                'audio_books.id',
                'title',
                'active',
                'genre_id',
                'year_id'
            ])
            ->with([
                'genre:id,name',
                'year:id,year',
                'authors:id,author'
            ]);
    }

    public function scopeDataForNoTimeToReadCompilation(Builder $query): Builder
    {
        $compilation = (new Compilation())->where('location', Compilation::NO_TIME_FOR_READ_LOCATION)->first();

        return $this
            ->select(
                'audio_books.id',
                'active',
                'title',
                'year_id',
                'genre_id'
            )->with([
                'genre:id,name',
                'authors:id,author',
                'year:id,year'
            ])->withExists(['bookCompilation as added' => function ($query) use ($compilation) {
                return $query->where('compilation_id', $compilation->id);
            }]);
    }

    public function booksForAddToAdminCompilations(int $compilationID): Builder
    {
        return $this
            ->select(['audio_books.id', 'title', 'active', 'year_id'])
            ->with([
                'genre:id,name',
                'authors:id,author',
                'year:id,year'
            ])
            ->withExists(['bookCompilation as added' => function ($query) use ($compilationID) {
                return $query->where('compilation_id', $compilationID);
            }]);
    }
}
