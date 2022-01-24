<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\DeleteQuoteRequest;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\SaveQuotesRequest;
use App\Api\Http\Requests\ShowQuotesRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Quote;
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
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(GetIdRequest $request, Quote $quote)
    {
        $quoteInBook = $quote->showInBook($request);
        return ApiAnswerService::successfulAnswerWithData($quoteInBook);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Quote $quote, DeleteQuoteRequest $request)
    {
        $rowsAffected = $quote->deleteQuote(Auth::id(), $request->quoteId);

       return ApiAnswerService::successfulAnswerWithData($rowsAffected);
    }
}
