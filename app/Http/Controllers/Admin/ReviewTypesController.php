<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewTypeRequest;
use App\Http\Requests\UpdateReviewTypeRequest;
use App\Models\ReviewType;
use function redirect;
use function request;
use function route;
use function view;

class ReviewTypesController extends Controller
{
    public function index(ReviewType $reviewTypes)
    {
        $reviewTypes = $reviewTypes->get();
        if (request()->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($reviewTypes);
        }

        return view('admin.reviews.types.index', compact('reviewTypes'));
    }

    public function create()
    {
        return view('admin.reviews.types.create');
    }

    public function store(StoreReviewTypeRequest $request, ReviewType $reviewType)
    {
        $reviewType->saveFromRequest($request);

        return redirect()->route('admin.review-types.edit', $reviewType)->with('success', 'Тип рецензии успешно добавлен!');
    }

    public function edit(ReviewType $reviewType)
    {
        return view('admin.reviews.types.edit', compact('reviewType'));
    }

    public function update(ReviewType $reviewType, UpdateReviewTypeRequest $request)
    {
        $reviewType->saveFromRequest($request);

        return redirect()->route('admin.review-types.edit', $reviewType)->with('success', 'Тип рецензии успешно обновлен!');
    }

    public function destroy(ReviewType $reviewType)
    {
        $reviewType->delete();
        return ApiAnswerService::redirect(route('admin.review-types.index'));
    }
}
