<?php

namespace App\Api\Http\Controllers;

use App\Api\Filters\CompilationFilter;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\ShowCompilationRequest;
use App\Api\Http\Requests\StoreCompilationRequest;
use App\Api\Services\ApiAnswerService;
use App\Api\Services\CompilationService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\Compilation;
use http\Env\Request;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;

class CompilationController extends Controller
{
    const COMPILAION_LIST_QUANTITY = 5;
    const COMPILAION_BLOCK_QUANTITY = 24;


    public function store(StoreCompilationRequest $request, CompilationService $compilation)
    {
        $user = Auth::user();
        $background = $request->file('image')->store('CompilationImages');
        $compilation->storeCompilation($request->title, $background, $request->description, $user->id, $request->compType);

        return ApiAnswerService::successfulAnswerWithData($compilation);

    }

    public function show(ShowCompilationRequest $request, CompilationFilter $compilationFilter)
    {

        $perList = $request->showType === Book::SHOW_TYPE_BLOCK ? self::COMPILAION_BLOCK_QUANTITY : self::COMPILAION_LIST_QUANTITY;

        $books = Compilation::filter($compilationFilter)
//            ->dum();
            ->paginate($perList);
//        dd($books);

        if ($request->showType === Book::SHOW_TYPE_LIST) {
            $collection = $books->getCollection();

            foreach ($collection as &$compilation) {

                foreach ($compilation->books as $book) {
                    if ($book->rates_avg === null) {
                        $book->rates_avg = 0;
                    }

                    unset($book->pivot);
                    foreach ($book->authors as $author) {
                        unset($author->pivot);
                    }
                }

            }

            $books->setCollection($collection);

        }


        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function showCompilationDetails(GetIdRequest $request)
    {

        $id = $request->id;
        $compilation = Compilation::
        with(['compilationable'
//        => function (MorphTo $morphTo) {
//                $morphTo->morphWith([
//                    Book::class => ['authors', 'image', 'bookGenres'],
//                    AudioBook::class => ['authors', 'image', 'genre']
//                ]);
//            }
            ])

//            Compilation::where('id', $id)
//            ->withCount('books')
//            ->with('books', function ($query) {
//                $b = new Book();
//                $ba = $b->getBook();
//                return $ba;
//                with([
//                    'authors',
//                    'image',
//                    'bookGenres',
//                ])
//                    ->select('id', 'title')
//                    ->withCount('rates')
//                    ->withAvg('rates as rates_avg', 'rates.rating');
//            })
            ->get();

        return ApiAnswerService::successfulAnswerWithData($compilation);

    }
}
