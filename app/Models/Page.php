<?php

namespace App\Models;

use App\Api\Filters\QueryFilter;
use App\Http\Requests\Admin\StorePageRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'link',
        'content',
        'page_number',
        'book_id'
    ];

    public static function create($fields){
        $page = new static();
        $page->fill($fields);
        $page->save();

        return $page;
    }

    public function saveFromRequest(StorePageRequest $request)
    {
        $this->book_id = $request->book_id;
        $this->content = $request->get('content');
        $this->page_number = $request->page_number;

        $this->save();
    }

    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'page_id', 'id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function chapter()
    {
        return $this->hasOne(Chapter::class);
    }
}
