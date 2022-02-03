<?php

namespace App\Api\Http\Controllers;

use App\Api\Factories\BookFactory;
use App\Api\Filters\AudioBookFilter;
use App\Api\Filters\BookFilter;
use App\Api\Filters\QueryFilter;
use App\Api\Http\Requests\ChangeBookStatusRequest;
use App\Api\Http\Requests\CurrentReadingRequest;
use App\Api\Http\Requests\DeleteBookFromCompilationRequest;
use App\Api\Http\Requests\DeleteBookFromUsersListRequst;
use App\Api\Http\Requests\GetBooksRequest;
use App\Api\Http\Requests\GetUserAuthorsRequest;
use App\Api\Http\Requests\GetUserBooksRequest;
use App\Api\Http\Requests\GetByLetterRequest;
use App\Api\Http\Requests\SaveBookToCompilationRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;

use App\Models\AudioBook;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\BookUser;
use App\Models\Compilation;
use App\Models\View;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    public function show(GetBooksRequest $request, BookFilter $bookFilter, AudioBookFilter $audioBookFilter, BookFactory $bookFactory)
    {
        $perPageList = $request->type === QueryFilter::TYPE_BOOK ? QueryFilter::PER_PAGE_LIST : QueryFilter::PER_PAGE_LIST_AUDIO;

        $perPage = $request->showType === QueryFilter::SHOW_TYPE_BLOCK ? QueryFilter::PER_PAGE_BLOCKS : $perPageList;


        $model = $bookFactory->createInstance($request->type);
        $books = $model->getBook()->filter($model instanceof Book ? $bookFilter : $audioBookFilter)
            ->paginate($perPage);


        $collection = $books->getCollection();
        foreach ($collection as &$book) {
            if ($book->rates_avg === null) {
                $book->rates_avg = 0;
            }

            if ($model instanceof Book) {

                foreach ($book->authors as $author) {
                    unset($author->pivot);
                }

                if ($book->relationLoaded('publishers')) {
                    foreach ($book->publishers as $publisher) {
                        unset($publisher->pivot);
                    }
                }

                foreach ($book->bookGenres as $genres) {
                    unset($genres->pivot);
                }
            }
        }

        $books->setCollection($collection);

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function showSingle($id, Book $book, View $view, Request $request)
    {
        $books = $book->singleBook($id);

        if ($books->rates_avg === null) {
            $books->rates_avg = 0;
        }
        foreach ($books->authors as $author) {
            unset($author->pivot);
        }
        foreach ($books->publishers as $publisher) {
            unset($publisher->pivot);
        }
        foreach ($books->bookGenres as $genres) {
            unset($genres->pivot);
        }


        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $book->getTypeAttribute());

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function deleteBookFromUsersList(DeleteBookFromUsersListRequst $request, BookUser $bookUser)
    {
        $user = Auth::user();

        $isUsersBook = $user->bookStatuses()->wherePivot('book_id', $request->book_id)->exists();

        if ($isUsersBook) {
            $bookUser->deleteBook($user->id, $request->book_id);
            return ApiAnswerService::successfulAnswerWithData($bookUser);

        }

        return ApiAnswerService::errorAnswer("Недостаточно прав для редактирования", Response::HTTP_FORBIDDEN);
    }

    public function saveBookToCompilation(SaveBookToCompilationRequest $request, BookCompilation $bookUsersCompilation)
    {

        $usersCompilation = Compilation::find($request->compilation_id);

        if ($usersCompilation?->created_by === Auth::id()) {

            $bookUsersCompilation->saveBookToCompilation($request->compilation_id, $request->book_id, $request->book_type);
            return ApiAnswerService::successfulAnswerWithData($bookUsersCompilation);


        }

        return ApiAnswerService::errorAnswer("У Вас нет прав на изменение этой подборки", Response::HTTP_FORBIDDEN);
    }

    public function deleteBookfromCompilation(DeleteBookFromCompilationRequest $request, BookCompilation $bookUsersCompilation)
    {

        $usersCompilation = Compilation::find($request->compilation_id);

        if ($usersCompilation?->created_by === Auth::id()) {

            $bookUsersCompilation->deleteBookfromCompilation($request->compilation_id, $request->book_id, $request->book_type);
            return ApiAnswerService::successfulAnswerWithData($bookUsersCompilation);

        }

        return ApiAnswerService::errorAnswer("У Вас нет прав на изменение этой подборки", Response::HTTP_FORBIDDEN);
    }

    public function changeBookStatus(ChangeBookStatusRequest $request, BookUser $bookUser): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user()->id;

        $bookUser->changeCreateStatus($user, $request->book_id, $request->status);

        return ApiAnswerService::successfulAnswerWithData($bookUser);
    }

    public function readBook(CurrentReadingRequest $request, Book $book): \Illuminate\Http\JsonResponse
    {
        $currentReading = $book->currentReading($request);

        return ApiAnswerService::successfulAnswerWithData($currentReading);
    }

    public function showByLetter(GetByLetterRequest $request, Book $books): \Illuminate\Http\JsonResponse
    {
        $books = $books->select(['id', 'title'])
            ->where('title', 'like', $request->letter . '%')->get();

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function getBookmarks(Book $book): \Illuminate\Http\JsonResponse
    {
        $bookmarks = $book->bookmarks()->with(['page' => function ($q) {
            $q->select('id', 'page_number');
        }])->get();

        foreach ($bookmarks as &$bookmark) {
            $bookmark->chapter = $book->chapters()->whereHas('page', function ($builder) use ($bookmark) {
                $builder->where('book_id', $bookmark->book_id);
                $builder->where('page_number', '<=', $bookmark->page->page_number);
            })
                ->addSelect('chapters.*', 'pages.id', 'pages.page_number')
                ->join('pages', 'chapters.page_id', '=', 'pages.id')
                ->orderBy('pages.page_number', 'desc')
                ->first();
        }

        return ApiAnswerService::successfulAnswerWithData($bookmarks);
    }

    public function showUserBooks(Request $request, BookFilter $bookFilter): \Illuminate\Http\JsonResponse
    {
        $books = \auth()->user()->bookStatuses()->filter($bookFilter)->get();

        return ApiAnswerService::successfulAnswerWithData($books);
    }


}
