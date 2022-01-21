<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\AuthorPageRequest;
use App\Api\Http\Requests\GetBooksRequest;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\AuthorToBook;
use App\Models\Book;
use App\Models\Compilation;
use App\Models\Review;
use App\Models\Series;
use App\Models\SimilarAuthors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorPageController extends Controller
{
    public function show(Request $request)
    {

        $authorWithSeries = Author::with([
            'books' => function ($qwery){
                $qwery->whereNull('series_id');
        }])->withCount('authorReviews')
            ->withCount('authorQuotes')
           ->findOrFail( $request->id);
//
//        $qutes = Author::withCount('authorReviews')
//                ->findOrFail($request->id);
      //}]);


//        $authorWithSeries->qutes = $qutes;
        /*with(['review'=>function($qwery){
            $qwery -> withCount();//where (author_id, $qwery->
        }])*/
        $series = Series::with('booksFullData')
            -> whereHas('books', function ($query) use ($authorWithSeries)
            {
            return $query
                ->whereHas('authors', function ($query) use ($authorWithSeries) {
                return $query->where('authors.id', $authorWithSeries->id);
                 });
        })->get();
        $authorWithSeries->series = $series;


        $compilation=Compilation::withCount('books')->get();
        $authorWithSeries->compilation=$compilation;

        $authorsSimilar=Author::with(['similarAuthors' => function ($query) use ($request){
            return $query->with('authors');
        }])
        ->findOrFail($request->id);
//
        return ApiAnswerService::successfulAnswerWithData(  $authorsSimilar);

    }
}
