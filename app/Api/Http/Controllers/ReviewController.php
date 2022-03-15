<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\DeleteReviewRequest;
use App\Api\Http\Requests\GetReviewRequest;
use App\Api\Http\Requests\SaveUpdateReviewRequest;
use App\Api\Interfaces\Types;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\ReviewType;
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

    public function saveUpdateReview(SaveUpdateReviewRequest $request): JsonResponse
    {
        $userId = Auth::id();
        $field = $this->getFieldName($request->type);
        $record = $this->reviewTypes[$request->type]
            ::updateOrCreate(
                [
                    'user_id' => $userId,
                    $field => $request->id
                ],
                [
                    'review_type_id' => $request->review_type,
                    'title' => $request->title,
                    'content' => $request->text,
                ]);
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

    public function showUserReviews()
    {
        // TODO: добавить кол-во лайков на отзыв + комментарии + просмотры
        $reviews = \auth()->user()->reviews()
            ->with(['book' => function ($query) {
                $query->with(['authors']);
            }])->get();

        return ApiAnswerService::successfulAnswerWithData($reviews);
    }

    public function getReviews(GetReviewRequest $request)
    {
        $model = new $this->reviewTypes[$request->type];
        return ApiAnswerService::successfulAnswerWithData($model->getReviews($request->id));
    }
}
