<?php

namespace App\Api\Http\Controllers;

use App\Api\Filters\CompilationFilter;
use App\Api\Filters\QueryFilter;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\ShowCompilationRequest;
use App\Api\Http\Requests\StoreCompilationRequest;
use App\Api\Http\Requests\UserCompilationsRequest;
use App\Api\Services\ApiAnswerService;
use App\Api\Services\CompilationService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Compilation;
use App\Models\User;
use App\Models\View;
use Illuminate\Support\Facades\Auth;

class CompilationController extends Controller
{
    const COMPILAION_LIST_QUANTITY = 5;
    const COMPILAION_BLOCK_QUANTITY = 24;
    const COMPILAION_USERS_QUANTITY = 9;

    public function store(StoreCompilationRequest $request, CompilationService $compilation)
    {
        $user = Auth::user();
        $background = $request->file('image')->store('CompilationImages');
        $compilation->storeCompilation($request->title, $background, $request->description, $user->id, $request->compType);

        return ApiAnswerService::successfulAnswerWithData($compilation);

    }

    public function show(ShowCompilationRequest $request, CompilationFilter $compilationFilter)
    {

        $perList = $request->showType === QueryFilter::SHOW_TYPE_BLOCK ? self::COMPILAION_BLOCK_QUANTITY : self::COMPILAION_LIST_QUANTITY;

        $books = Compilation::filter($compilationFilter)
            ->paginate($perList);

        if ($request->showType === QueryFilter::SHOW_TYPE_LIST) {

            $collection = $books->getCollection();

            foreach ($collection as &$compilation) {

                foreach ($compilation->books as $book) {
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

    public function showCompilationDetails(GetIdRequest $request, CompilationService $compilationService, View $view)
    {
        $compilation = Compilation::select('id', 'title', 'background', 'description', 'type')
            ->withCount(['books', 'audioBooks'])
            ->findOrfail($request->id);

        $books = $compilationService->showCompilationDetails($request->id);

        $compilation->generalBooksCount = $compilation->books_count + $compilation->audio_books_count;

        $view->addView(\auth('api')->user()?->id, $request->ip(), $compilation->id, $compilation->getTypeAttribute());

        return ApiAnswerService::successfulAnswerWithData(['compilation' => $compilation, 'books' => $books]);
    }

    public function showUserCompilations(UserCompilationsRequest $request, CompilationFilter $compilationFilter): \Illuminate\Http\JsonResponse
    {
        $compilation = Compilation::filter($compilationFilter)->paginate(self::COMPILAION_USERS_QUANTITY);

        $compilation->map(function ($query) {
            $query->total_books_count = $query->books_count + $query->audio_books_count;
        });

        return ApiAnswerService::successfulAnswerWithData($compilation);
    }

    public function countTypesInUserLists(User $user)
    {
        return ApiAnswerService::successfulAnswerWithData($user->countTypesInLists());
    }
}
