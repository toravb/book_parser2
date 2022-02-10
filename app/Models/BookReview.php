<?php

namespace App\Models;

use App\Api\Http\Controllers\MainPageController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BookReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'review_type_id',
        'title',
        'content'
    ];


    /*  public static function create($fields)
      {
          $reviews = new static();
          $reviews->fill($fields);
          $reviews->save();

          return $reviews;
      }
      public function edit($fields)
      {
          $this->fill($fields);
          $this->save();
      }*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable', 'like_type', 'like_id');
    }

    public function reviewTypes()
    {
        return $this->belongsTo(ReviewType::class);
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function comments(): hasMany
    {
        return $this->hasMany(BookCommentReview::class);
    }


    public function latestReviewBookUser()
    {
        return $this
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'avatar');
                },
                'book' => function ($query) {
                    $query->select('id', 'title')
                        ->with([
                            'image' => function ($query) {
                                $query->select('book_id', 'link');
                            },
                            'authors' => function ($query) {
                                $query->select('authors.id', 'author');
                            },
                        ]);
                },
            ])
            ->withCount(['views', 'likes', 'comments'])
            ->orderBy('created_at')
            ->paginate(MainPageController::REVIEWS_PAGINATION, ['*'], 'reviews-list-page');
    }
}
