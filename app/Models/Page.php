<?php

namespace App\Models;

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

    public function edit($fields){
        $this->fill($fields);
        $this->save();
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
