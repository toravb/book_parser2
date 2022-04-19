<?php

namespace App\Models;

use App\Http\Requests\StoreReviewTypeRequest;
use Illuminate\Database\Eloquent\Model;

class ReviewType extends Model
{
    protected $fillable = ['type'];

    public $timestamps = false;

    public function books()
    {
        return $this->hasMany(BookReview::class);
    }

    public function audioBooks()
    {
        return $this->hasMany(AudioBookReview::class);
    }

    public function saveFromRequest(StoreReviewTypeRequest $request)
    {
        $this->type = $request->type;
        $this->save();
    }
}
