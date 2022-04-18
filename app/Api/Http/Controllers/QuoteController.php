<?php

namespace App\Api\Http\Controllers;

use App\Api\Filters\QuoteFilter;
use App\Api\Http\Requests\DeleteQuoteRequest;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\SaveQuotesRequest;
use App\Api\Http\Requests\ShowQuotesRequest;
use App\Api\Http\Requests\UpdateQuoteRequest;
use App\Api\Http\Requests\UserQuotesRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetQuotesForBookRequest;
use App\Models\Quote;
use App\Models\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(ShowQuotesRequest $request, Quote $quotes)
    {
        $quoteAll = $quotes->showAll(Auth::id(), $request);
        return ApiAnswerService::successfulAnswerWithData($quoteAll);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveQuotesRequest $request
     * @param Quote $quote
     * @return JsonResponse
     */
    public function store(SaveQuotesRequest $request, Quote $quote)
    {
        $quote->store(Auth::id(), $request);
        return ApiAnswerService::successfulAnswerWithData($quote);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @param GetIdRequest $request
     * @param Quote $quote
     * @param View $view
     * @return JsonResponse
     */
    public function show($id, GetIdRequest $request, Quote $quote, View $view)
    {
        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $quote->getTypeAttribute());
        $quoteInBook = $quote->showInBook($request);
        return ApiAnswerService::successfulAnswerWithData($quoteInBook);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse

    {
        $quoteForUpdate = $quote->findOrFail($request->id);

        if ($quoteForUpdate?->user_id === \auth()->id()) {
            $quoteForUpdate->store(\auth()->id(), $request);
            return ApiAnswerService::successfulAnswerWithData($quoteForUpdate);
        }

        return ApiAnswerService::errorAnswer('Нет прав для редактирования', Response::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Quote $quote
     * @param DeleteQuoteRequest $request
     * @return JsonResponse
     */
    public function destroy(Quote $quote, DeleteQuoteRequest $request)
    {
        $rowsAffected = $quote->deleteQuote(Auth::id(), $request->quoteId);

        return ApiAnswerService::successfulAnswerWithData($rowsAffected);
    }

    public function showUserQuotes(UserQuotesRequest $request, Quote $quotes, QuoteFilter $quoteFilter)
    {
        $userQuotes = $quotes->showUserQuotes(\auth()->id())->filter($quoteFilter)->get();

        return ApiAnswerService::successfulAnswerWithData($userQuotes);
    }

    public function getQuotesForBookPage(GetQuotesForBookRequest $request, Quote $quote)
    {
        return ApiAnswerService::successfulAnswerWithData($quote->getQuotesForBookPage($request->id));
    }
}
