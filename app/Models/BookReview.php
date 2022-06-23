<?php

namespace App\Models;

use App\Api\Filters\BookFilter;
use App\Api\Http\Controllers\MainPageController;
use App\Api\Interfaces\ReviewInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BookReview extends Model implements ReviewInterface
{
    const PER_PAGE = 3;

    use HasFactory;
    use \Awobaz\Compoships\Compoships;

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

    public function likes(): HasMany
    {
        return $this->hasMany(BookReviewLike::class);
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

    public function UserBookRate(): HasOne
    {
        return $this->hasOne(Rate::class, ['user_id', 'book_id'], ['user_id', 'book_id']);
    }

    public function latestReviewBookUser()
    {
        return $this
            ->with([
                'user:id,nickname,avatar',
                'book:id,title',
                'book.image:book_id,link',
                'book.authors:id,author'
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
            ->paginate(self::PER_PAGE);
    }

    public static function getNotificationReview(int $reviewId)
    {
        return self::with([
            'book' => function ($query) {
                return $query->select('id', 'title');
            }
        ])
            ->findOrFail($reviewId);
    }

    public function getBookObject()
    {
        return $this->books;
    }

    public function getUserReviews(int $userID, $request): Collection
    {
        return $this->where('user_id', $userID)
            ->whereHas('book', function ($query) use ($request) {
                return $query->filter(new BookFilter($request));
            })
            ->select([
                'id',
                'title',
                'content',
                'created_at',
                'book_id'
            ])
            ->with([
                'book:id,title',
                'book.image:book_id,public_path as link',
                'book.authors:id,author'
            ])
            ->withCount([
                'likes',
                'views',
                'comments'
            ])
            ->get();
    }
}
