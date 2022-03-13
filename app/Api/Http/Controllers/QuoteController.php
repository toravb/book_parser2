<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\DeleteQuoteRequest;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\SaveQuotesRequest;
use App\Api\Http\Requests\ShowQuotesRequest;
use App\Api\Http\Requests\UserQuotesRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetQuotesForBookRequest;
use App\Models\Quote;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ShowQuotesRequest $request, Quote $quotes)
    {
        $quoteAll = $quotes->showAll(Auth::id(), $request);
        return ApiAnswerService::successfulAnswerWithData($quoteAll);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SaveQuotesRequest $request, Quote $quote)
    {
        $quote->store(Auth::id(), $request);
        return ApiAnswerService::successfulAnswerWithData($quote);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, GetIdRequest $request, Quote $quote, View $view)
    {
        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $quote->getTypeAttribute());
        $quoteInBook = $quote->showInBook($request);
        return ApiAnswerService::successfulAnswerWithData($quoteInBook);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Quote $quote, DeleteQuoteRequest $request)
    {
        $rowsAffected = $quote->deleteQuote(Auth::id(), $request->quoteId);

        return ApiAnswerService::successfulAnswerWithData($rowsAffected);
    }

    // TODO: user quotes - уточнить
    public function showUserQuotes(UserQuotesRequest $request)
    {
        $quotes = \auth()->user()->quotes()
            ->with(['book' => function ($query) {
                $query->with(['authors']);
            }])->get();

        return ApiAnswerService::successfulAnswerWithData($quotes);

    }

    public function getQuotesForBookPage (GetQuotesForBookRequest $request, Quote $quote)
    {
        return ApiAnswerService::successfulAnswerWithData($quote->getQuotesForBookPage($request->id));
    }
}
