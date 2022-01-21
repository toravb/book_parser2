<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function store(int $userId, $request)
    {
        $this->user_id = $userId;
        $this->book_id = $request->bookId;
        $this->page_id = $request->pageId;
        $this->content = $request->text;
        $this->color = $request->color;
        $this->save();

    }


}
