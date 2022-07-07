<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Requests\UpdateUserCompilationRequest;
use App\Api\Interfaces\SearchModelInterface;
use App\Api\Traits\ElasticSearchTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Compilation extends Model implements SearchModelInterface
{
    use HasFactory, ElasticSearchTrait;


    const SORT_BY_DATE = '1';
    const SORT_BY_ALPHABET = '2';
    const SORT_BY_VIEWS = '3';
    const COMPILATION_USER = '1';
    const COMPILATION_ADMIN = '2';
    const COMPILATION_ALL = '3';
    const COMPILATION_PER_PAGE = 20;
    const CATEGORY_ALL = '3';

    public function toArray()
    {
        if ($this->background and \Storage::exists($this->background)) {
            $this->background = \Storage::url($this->background);
        }

        return parent::toArray();
    }

    public static array $availableCompilationableTypes = [
        QueryFilter::TYPE_BOOK,
        QueryFilter::TYPE_AUDIO_BOOK,
        QueryFilter::TYPE_ALL
    ];

    public function getTypeAttribute(): string
    {
        return 'compilation';
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function compilationable()
    {
        return $this->morphTo('compilationable', 'compilationable_type', 'compilationable_id');
    }

    public function books()
    {
        return $this->morphedByMany(
            Book::class,
            'compilationable',
            'book_compilation',
            'compilation_id',
            'compilationable_id',
            'id',
            'id');
    }

    public function audioBooks()
    {
        return $this->morphedByMany(
            AudioBook::class,
            'compilationable',
            'book_compilation',
            'compilation_id',
            'compilationable_id',
            'id',
            'id');
    }

    public function compilationUsers()
    {
        return $this->belongsToMany(User::class)
            ->where('id', auth('api')->id());
    }

    public function compilationType()
    {
        return $this->belongsTo(CompilationType::class);
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function withSumAudioAndBooksCount()
    {
        $compilations = $this
            ->withCount([
                'books',
                'audioBooks',
                'views'
            ])
            ->whereNull('location')
            ->whereNotNull('type')
            ->orderBy('created_at')
            ->limit(20)
            ->get();

        $compilations->map(function ($compilation) {
            $compilation->total_count = $compilation->books_count + $compilation->audio_books_count;
        });

        return $compilations;
    }

    public function searchByType(int $type): Compilation|null
    {
        return $this
            ->compilationWithBooks()
            ->where('type', $type)
            ->first();
    }

    public function toSearchArray(): array
    {
        return [
            'title' => $this->title
        ];
    }

    public function baseSearchQuery(): Builder
    {
        return $this->select('id', 'title', 'background')->withCount(['books', 'audioBooks']);
    }

    public function getElasticKey()
    {
        return $this->getKey();
    }

    public function scopeCompilationWithBooks(): Builder
    {
        return $this
            ->select(['id', 'title'])
            ->with(['books' => function (MorphToMany $query) {
                $query
                    ->where('active', true)
                    ->select(['id', 'title'])
                    ->with([
                        'authors:author',
                        'genres:name',
                        'image:book_id,link'])
                    ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
                    ->withCount('views')
                    ->latest()
                    ->limit(20);
            }]);
    }

    public function newBooksMainPage(int $location): Compilation|null
    {
        return $this
            ->compilationWithBooks()
            ->where('location', $location)
            ->first();
    }

    public function noTimeToReadMayListen(int $location): Compilation|null
    {
        return $this
            ->select(['id', 'title'])
            ->with(['audioBooks' => function ($query) {
                $query
                    ->where('active', true)
                    ->select([
                        'id',
                        'title',
                        'genre_id'
                    ])
                    ->with([
                        'authors:author',
                        'genre:id,name',
                        'image:book_id,link'
                    ])
                    ->withAggregate('rates as rates_avg', 'Coalesce( avg( rates.rating ), 0 )')
                    ->withCount('views')
                    ->latest()
                    ->limit(20);
            }])
            ->where('location', $location)
            ->first();
    }

    public function compilationUpdate(UpdateUserCompilationRequest $request): Compilation
    {
        if ($request->background) {
            if ($this->background) \Storage::delete($this->background);

            $this->background = \Storage::put('CompilationImages', $request->background);
        }

        $this->title = $request->title;
        $this->description = $request->description;
        $this->save();

        return $this;
    }

    public function storeCompilation(
        string $title,
        string $backgroud,
        string $description,
        int    $created_by,
        int    $type = null
    ): Compilation
    {
        $this->title = $title;
        $this->background = $backgroud;
        $this->description = $description;
        $this->created_by = $created_by;
        $this->type = $type;
        $this->save();

        return $this;
    }
}
