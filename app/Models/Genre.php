<?php

namespace App\Models;

use App\Http\Requests\StoreGenreRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function books(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function audioBooks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AudioBook::class);
    }
}
