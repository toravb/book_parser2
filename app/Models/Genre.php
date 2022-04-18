<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Http\Requests\StoreGenreRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Genre extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];
    protected $fillable = ['name'];

    public function saveFromRequest(StoreGenreRequest $request)
    {
        $this->name = $request->name;
        $this->is_hidden = (bool)$request->is_hidden;
        $this->save();
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function audioBooks(): BelongsToMany
    {
        return $this->belongsToMany(AudioBook::class);
    }

    public function banners(): BelongsToMany
    {
        return $this->belongsToMany(Banner::class);
    }

    public function booksCount(): array|Collection
    {
        return $this->select('id', 'name')
            ->withCount('books')
            ->get();
    }

    public function audioBooksCount(): array|Collection
    {
        return $this->select('id', 'name')
            ->withCount('audioBooks')
            ->get();
    }
}
