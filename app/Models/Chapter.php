<?php

namespace App\Models;

use App\Http\Requests\BookmarkRequest;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['title','book_id', 'page_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

}
