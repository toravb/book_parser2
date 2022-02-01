<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Interfaces\BookInterface;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AudioBook extends Model implements BookInterface
{
    use HasFactory, Sluggable;

    const TYPE_AUDIO_BOOK = 'audioBooks';

    protected $fillable = [
        'title',
        'description',
        'params',
        'genre_id',
        'series_id',
        'link_id',
        'litres'
    ];

    protected $appends = [
        'type'
    ];

    public function getTypeAttribute()
    {

        return 'audioBooks';

    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function create($fields)
    {
        $book = new static();
        $book->fill($fields);
        $book->save();

        return $book;
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
        return $this->belongsTo(AudioGenre::class, 'genre_id', 'id');
    }

    public function series()
    {
        return $this->belongsTo(
            AudioSeries::class,
            'series_id',
            'id'
        );
    }

    public function authors()
    {
        return $this->hasManyThrough(
            AudioAuthor::class,
            AudioAuthorsToBook::class,
            'book_id',
            'id',
            'id',
            'author_id'
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
        return $this->hasMany(AudioBookUser::class);
    }


    public function getBook(): Builder
    {
        return $this->with([
            'authors',
            'image',
            'genre',

        ])
            ->select('id', 'title', 'year_id', 'genre_id')
            ->withCount('views')
            ->withAvg('rates as rates_avg', 'rates.rating');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }
}
