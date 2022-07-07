<?php

namespace App\Api\Http\Controllers;

use App\Api\Filters\CompilationFilter;
use App\Api\Filters\QueryFilter;
use App\Api\Http\Requests\AddCompilationToFavoriteRequest;
use App\Api\Http\Requests\DestroyCompilationUserRequest;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\RemoveCompilationFromFavoriteRequest;
use App\Api\Http\Requests\ShowCompilationRequest;
use App\Api\Http\Requests\StoreCompilationRequest;
use App\Api\Http\Requests\UpdateUserCompilationRequest;
use App\Api\Http\Requests\UserCompilationsRequest;
use App\Api\Services\ApiAnswerService;
use App\Api\Services\CompilationService;
use App\Http\Controllers\Controller;
use App\Models\Compilation;
use App\Models\CompilationUser;
use App\Models\User;
use App\Models\View;
use Illuminate\Support\Facades\Auth;

class CompilationController extends Controller
{
    const COMPILAION_LIST_QUANTITY = 5;
    const COMPILAION_BLOCK_QUANTITY = 24;
    const COMPILAION_USERS_QUANTITY = 9;

    public function store(StoreCompilationRequest $request, Compilation $compilation)
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

        $compilation->in_favorite = $compilation->compilationUsers()->exists();

        $books = $compilationService->showCompilationDetails($request->id);

        $compilation->generalBooksCount = $compilation->books_count + $compilation->audio_books_count;

        $view->addView(\auth('api')->user()?->id, $request->ip(), $compilation->id, $compilation->getTypeAttribute());

        return ApiAnswerService::successfulAnswerWithData(['compilation' => $compilation, 'books' => $books]);
    }

    public function showUserCompilations(UserCompilationsRequest $request, CompilationFilter $compilationFilter): \Illuminate\Http\JsonResponse
    {
        $compilation = Compilation::query()->whereNull('location')->filter($compilationFilter)->paginate(self::COMPILAION_USERS_QUANTITY);

        $compilation->map(function ($query) {
            $query->total_books_count = $query->books_count + $query->audio_books_count;
        });

        return ApiAnswerService::successfulAnswerWithData($compilation);
    }

    public function countTypesInUserLists(User $user)
    {
        return ApiAnswerService::successfulAnswerWithData($user->countTypesInLists());
    }

    public function editUsersCompilation(UpdateUserCompilationRequest $request, Compilation $compilations)
    {
        try {
            $compilation = $compilations->where('created_by', Auth::id())->findOrFail($request->id);
        } catch (\Exception $e) {
            return ApiAnswerService::errorAnswer('Нет прав для редактирования!', 403);
        }
        return ApiAnswerService::successfulAnswerWithData($compilation->compilationUpdate($request));
    }

    public function deleteUserCompilation(DestroyCompilationUserRequest $request, Compilation $compilations)
    {
        try {
            $compilation = $compilations->where('created_by', Auth::id())->findOrFail($request->id);
        } catch (\Exception $e) {
            return ApiAnswerService::errorAnswer('Нет прав для удаления!', 403);
        }
        return ApiAnswerService::successfulAnswerWithData($compilation->delete());
    }

    public function addCompilationToFavorite(CompilationUser $compilationUser, AddCompilationToFavoriteRequest $request)
    {
        return ApiAnswerService::successfulAnswerWithData($compilationUser->addToFavorite(Auth::id(), $request->compilation_id));
    }

    public function removeCompilationFromFavorite(CompilationUser $compilationUser, RemoveCompilationFromFavoriteRequest $request)
    {
        return ApiAnswerService::successfulAnswerWithData($compilationUser->removeFromFavorite($request->compilation_id));
    }
}
