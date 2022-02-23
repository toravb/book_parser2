<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGenre extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];
    protected $fillable = ['name'];


    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function booksCount()
    {
        return $this->withCount('books')->get();
    }

    public function index()
    {
        return $this->orderBy('name', 'asc')->get();
    }

    public function storeUpdates(int $id, string $name)
    {
        return $this->where('id', $id)
            ->update(['name' => $name]);
    }

}
