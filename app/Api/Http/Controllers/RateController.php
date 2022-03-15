<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\StoreAudioBookRatingRequest;
use App\Api\Http\Requests\StoreRatingValidation;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function store(StoreRatingValidation $request, Rate $rating)
    {
        $rating->store(\auth()->id(), $request->book_id, $request->rating);

        return ApiAnswerService::successfulAnswerWithData($rating->returnedRate($request->book_id));
    }

    public function storeRateAudioBook(StoreAudioBookRatingRequest $request, Rate $rating, Book $book)
    {

        $rating->storeAudioBookRating(\auth()->id(), $request->audio_book_id, $request->rating);

        return ApiAnswerService::successfulAnswerWithData($rating->returnedAudioRate($request->audio_book_id));
    }
}
