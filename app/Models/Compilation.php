<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Compilation extends Model
{
    use HasFactory;


    const SORT_BY_DATE = '1';
    const SORT_BY_ALPHABET = '2';
    const COMPILATION_USER = '1';
    const COMPILATION_ADMIN = '2';
    const COMPILATION_ALL = '3';
    const COMPILATION_PER_PAGE = 20;

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function compilationable() {
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

    public function compilationType(){
        return $this->belongsTo(CompilationType::class);
    }


    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }






}
