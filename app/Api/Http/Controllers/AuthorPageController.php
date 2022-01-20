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
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorPageController extends Controller
{
    public function show(Request $request)
    {

        $authorWithSeries = Author::with([
            'books' => function ($qwery){
                $qwery->whereNull('series_id');
        }])
            //->select( 'title', 'link', 'param')

            ->findOrFail( $request->id);
//
//        $books = Book::with([
//            'authors',
//            'image',
//            'bookGenres',
//           'genres',
//            'reviews',
//            'quotes'])
//            ->where('series_id', null)
//
//            ->select('id', 'title')
//
//            ->withAvg('rates as rates_avg', 'rates.rating')
//            ->get();
   /*     $authorWhithoutSeries = Author::with([
            'books' => function ($qwery){
                $qwery->where('series_id', null);
            }])
            ->findOrFail( $request->id);*/
            //;

      //  $se = Book::where('series_id', null)->findOrFail( $request->id);
        $series = Series::with('booksFullData')
            -> whereHas('books', function ($query) use ($authorWithSeries)
            {
            return $query
                ->whereHas('authors', function ($query) use ($authorWithSeries) {
                return $query->where('authors.id', $authorWithSeries->id);
                 });
        })->get();
        $authorWithSeries->series = $series;

//dd($series);
        return ApiAnswerService::successfulAnswerWithData($authorWithSeries);
      //  return response()->json(['author' => $authorWithSeries]);
    }
}
