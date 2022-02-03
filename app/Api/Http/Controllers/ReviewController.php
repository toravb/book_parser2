<?php

namespace App\Api\Http\Controllers;

use App\Api\Interfaces\Types;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteReviewRequest;
use App\Http\Requests\SaveUpdateReviewRequest;
use App\Models\ReviewType;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    private array $reviewTypes;

    public function __construct(Types $types)
    {
        $this->reviewTypes = $types->getReviewTypes();
    }

    public function saveUpdateReview(SaveUpdateReviewRequest $request): \Illuminate\Http\JsonResponse
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

    private function getFieldName($type)
    {
        return $type . '_id';
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
}
