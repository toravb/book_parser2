<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Api\Http\Controllers\MainPageController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compilation extends Model
{
    use HasFactory;


    const SORT_BY_DATE = '1';
    const SORT_BY_ALPHABET = '2';
    const COMPILATION_USER = '1';
    const COMPILATION_ADMIN = '2';
    const COMPILATION_ALL = '3';
    const COMPILATION_PER_PAGE = 20;

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
        return $this->belongsToMany(CompilationUser::class);
    }

    public function compilationType()
    {
        return $this->belongsTo(CompilationType::class);
    }

    public function views(): \Illuminate\Database\Eloquent\Relations\MorphMany
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
            ->whereNotNull('type')
            ->orderBy('created_at')
            ->paginate(MainPageController::COMPILATION_PAGINATION, ['*'], 'compilationPage');

        $compilations->map(function ($compilation) {
            $compilation->total_count = $compilation->books_count + $compilation->audio_books_count;
        });

        return $compilations;
    }

}
