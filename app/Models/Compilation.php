<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Requests\UpdateUserCompilationRequest;
use App\Api\Interfaces\SearchModelInterface;
use App\Api\Traits\ElasticSearchTrait;
use App\Http\Requests\Admin\StoreCompilationRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;

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
    const NOVELTIES_LOCATION = 1;
    const NO_TIME_FOR_READ_LOCATION = 2;

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

    public function compilationType(): BelongsTo
    {
        return $this->belongsTo(CompilationType::class, 'type_id', 'id');
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
            ->whereNotNull('type_id')
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
            ->where('type_id', $type)
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
        if ($request->image) {
            if ($this->background) \Storage::delete($this->background);

            $this->background = \Storage::put('CompilationImages', $request->image);
        }

        $this->title = $request->title;
        $this->description = $request->description;
        $this->save();

        return $this;
    }

    public function storeCompilation(
        string $title,
        string $background,
        string $description,
        int    $created_by,
        int    $type = null,
        int    $location = null
    ): Compilation
    {
        $this->title = $title;
        $this->background = $background;
        $this->description = $description;
        $this->created_by = $created_by;
        $this->type_id = $type;
        $this->location = $location;
        $this->save();

        return $this;
    }

    public function createMainPageAdminCompilation(int $location)
    {
        $compilation = new Compilation();

        $compilation->title = '';
        $compilation->background = '';
        $compilation->description = '';
        $compilation->created_by = auth()->id();
        $compilation->location = $location;
        $compilation->save();
    }

    public function addBookToAdminCompilation(int $bookID, string $type, int $location = 0, int $compilationID = 0)
    {
        $bookCompilation = new BookCompilation();

        if (!$location == 0) {
            $compilations = new Compilation();
            $compilationAdmin = $compilations->where('location', $location)->first();
            $bookCompilation->compilation_id = $compilationAdmin->id;
        } else {
            $bookCompilation->compilation_id = $compilationID;
        }

        $bookCompilation->compilationable_id = $bookID;
        $bookCompilation->compilationable_type = $type;
        $bookCompilation->save();

        return $bookCompilation;
    }

    public function removeBookFromAdminCompilation(int $bookID, int $location, int $compilationID = 0)
    {
        if (!$location == 0) {
            $compilation = (new Compilation())
                ->where('location', $location)
                ->first();
            $id = $compilation->id;
        } else {
            $id = $compilationID;
        }

        $bookCompilation = new BookCompilation();
        $bookCompilation
            ->where('compilation_id', $id)
            ->where('compilationable_id', $bookID)
            ->delete();
    }

    public function compilationsForAdmin()
    {
        return $this->whereNotNull('type_id')->with('compilationType:id,name');
    }

    public function saveFromRequest(StoreCompilationRequest $request)
    {
        $this->title = $request->title;
        $this->description = $request->description ?? '';
        $this->type_id = $request->type_id;
        $this->created_by = $request->created_by ?? Auth::id();


        if ($request->background_image_remove and $this->background) {
            \Storage::delete($this->background);
            $this->background = 'нет изображения';
        }

        if ($request->background) {
            if ($this->background) \Storage::delete($this->background);

            $this->background = \Storage::put('CompilationImages', $request->background);
        }
        $this->save();
    }

    public function adminCompilationWithBooks()
    {
        return $this->with([
            'books:id,title',
            'books.image:book_id,public_path',
            'audioBooks:id,title',
            'audioBooks.image:book_id,public_path'
        ])->get();
    }
}
