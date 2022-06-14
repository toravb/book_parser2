<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\DeleteReviewRequest;
use App\Api\Http\Requests\GetReviewRequest;
use App\Api\Http\Requests\SaveReviewRequest;
use App\Api\Http\Requests\SearchInUserReviewsRequest;
use App\Api\Http\Requests\UpdateReviewRequest;
use App\Api\Interfaces\Types;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBookReview;
use App\Models\BookReview;
use App\Models\ReviewType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    private array $reviewTypes;

    private function getFieldName($type)
    {
        return $type . '_id';
    }

    public function __construct(Types $types)
    {
        $this->reviewTypes = $types->getReviewTypes();
    }

    public function updateReview(UpdateReviewRequest $request, User $users): JsonResponse
    {
        $userId = Auth::id();
        $field = $this->getFieldName($request->type);

        $record = $this->reviewTypes[$request->type]::where([
            'user_id' => $userId,
            $field => $request->id
        ])->firstOrFail();

        $record->update([
            'review_type_id' => $request->review_type,
            'title' => $request->title,
            'content' => $request->text,
        ]);

        $record->user = $users->select('id', 'name', 'avatar', 'nickname')->findOrFail($userId);

        return ApiAnswerService::successfulAnswerWithData($record);
    }

    public function index()
    {
        return ApiAnswerService::successfulAnswerWithData(ReviewType::all());
    }

    public function delete(DeleteReviewRequest $request)
    {
        $field = $this->getFieldName($request->type);
        $review = $this->reviewTypes[$request->type]::where($field, $request->id)
            ->where('user_id', Auth::id())
            ->delete();
        return ApiAnswerService::successfulAnswerWithData($review);
    }

    public function showUserReviews(SearchInUserReviewsRequest $request, BookReview $bookReview, AudioBookReview $audioBookReview)
    {
        $userID = \auth()->user()->id;

        $bookReviews = $bookReview->getUserReviews($userID, $request);
        $audioBookReviews = $audioBookReview->getUserReviews($userID, $request);

        $reviews = $bookReviews->concat($audioBookReviews);

        return ApiAnswerService::successfulAnswerWithData($reviews);
    }

    public function getReviews(GetReviewRequest $request)
    {
        $model = new $this->reviewTypes[$request->type];
        return ApiAnswerService::successfulAnswerWithData($model->getReviews($request->id));
    }

    public function store(SaveReviewRequest $request, User $users)
    {
        $userId = Auth::id();
        $field = $this->getFieldName($request->type);
        $record = $this->reviewTypes[$request->type]
            ::create(
                [
                    'user_id' => $userId,
                    $field => $request->id,
                    'review_type_id' => $request->review_type,
                    'title' => $request->title,
                    'content' => $request->text,
                ]);
        $record->user = $users->select('id', 'name', 'avatar', 'nickname')->findOrFail($userId);
        return ApiAnswerService::successfulAnswerWithData($record);
    }
}
