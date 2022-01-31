<?php

namespace App\Api\Http\Controllers;

use App\Api\Factories\BookFactory;
use App\Api\Filters\AudioBookFilter;
use App\Api\Http\Requests\GetAudioBooksRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\View;
use Illuminate\Http\Request;

class AudioBookController extends Controller
{
    public function show(GetAudioBooksRequest $request, AudioBookFilter $audioFilter, BookFactory $bookFactory)
    {
        $model = $bookFactory->createInstance($request->type);
        $audioBook = $model->getBook()->filter($audioFilter)->paginate(1);

        return ApiAnswerService::successfulAnswerWithData($audioBook->getBook());
    }

    public function showAudioBookDetails($id, AudioBook $book, View $view, Request $request)
    {
        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $book->getTypeAttribute());
    }

}
