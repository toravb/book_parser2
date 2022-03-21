<?php

namespace App\Models;

use App\Api\Http\Controllers\MainPageController;
use App\Api\Interfaces\ReviewInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BookReview extends Model implements ReviewInterface
{
    const PERPAGE = 3;

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

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable', 'like_type', 'like_id');
    }

    public function reviewTypes()
    {
        return $this->belongsTo(ReviewType::class, 'review_type_id', 'id');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function comments(): hasMany
    {
        return $this->hasMany(BookReviewComment::class);
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
            ->limit(20)
            ->get();
    }

    public function getReviews(int $id)
    {
        return $this
            ->with([
                'user:id,avatar,nickname',
                'reviewTypes'
            ])
            ->where('book_id', $id)
            ->withCount(['likes', 'comments'])
            ->paginate(self::PERPAGE);
    }
}
