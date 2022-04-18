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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AudioBook extends Model implements BookInterface, SearchModelInterface
{
    use HasFactory, ElasticSearchTrait;

    const WANT_LISTEN = '1';
    const LISTENING = '2';
    const HAD_LISTEN = '3';
    const MAIN_PAGE_PAGINATE = 6;
    public static array $availableListeningStatuses = [
        self::WANT_LISTEN,
        self::LISTENING,
        self::HAD_LISTEN
    ];
    protected $fillable = [
        'title',
        'description',
        'params',
        'series_id',
        'link_id',
        'litres'
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

    public function audiobooks()
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
        return $this->morphOne(BookCompilation::class, 'bookCompilationable');
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
            //TODO: после выяснения подробностей нужно добавить:
            // Продолжительность файла
            // После, дописать доку
            ->where('id', $bookId)
            ->select('id', 'title', 'description', 'year_id', 'series_id', 'link_id', 'genre_id')
            ->withCount(['views', 'audioBookStatuses as listeners_count', 'rates', 'reviews'])
            ->withAvg('rates as rates_avg', 'rates.rating')
            ->firstOrFail();
    }

    public function getBookForLetterFilter(): Builder
    {
        return $this
            ->with(['authors' => function ($query) {
                return $query->select('name');
            }])
            ->select(['id', 'title'])
            ->withCount('rates')
            ->withAvg('rates as rates_avg', 'rates.rating');
    }

    public function mainPagePaginateList()
    {
        $audioBookList = $this
            ->select([
                'id',
                'title',
                'link_id',
                'genre_id'
            ])
            ->with([
                'authors:author',
                'genre:id,name',
                'image:book_id,link'
            ])
            ->withAvg('rates as rates_avg', 'rates.rating')
            ->withCount('views')
            ->limit(20)
            ->get();

        $audioBookList->map(function ($compilation) {
            if ($compilation->rates_avg === null) {
                $compilation->rates_avg = 0;
            }
        });

        return $audioBookList;
    }

    public function noveltiesBooks(): Builder
    {
        return $this
            ->select('audio_books.id', 'audio_books.title', 'audio_books.year_id', 'genre_id')
            ->with([
                'genre:id,name',
                'authors:id,author',
                'image:book_id,link',
                'year:id,year'
            ])
            ->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->when($this->avg('audio_books.year_id') > 0, function ($q){
                $q->join('years', 'years.id', '=', 'audio_books.year_id');
            });
    }

    public function baseSearchQuery(): Builder
    {
        return $this->getBook();
    }

    public function getBook(): Builder
    {
        return $this->with([
            'authors',
            'image',
            'genre',
        ])
            ->select('id', 'title', 'year_id')
            ->withCount('views')
            ->withAvg('rates as rates_avg', 'rates.rating');
    }

    public function getElasticKey()
    {
        return $this->getKey();
    }

    public function getForAdmin()
    {
        return $this
            ->select(
                'audio_books.id',
                'audio_books.active',
                'audio_books.title',
                'audio_books.year_id',
                'audio_books.genre_id'
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
            'genre_id' => $genre,
            'series_id' => $series,
            'year_id' => $yearId
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
}
