<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\BooksChapterValidation;
use App\Api\Http\Requests\ChangeBookStatusRequest;
use App\Api\Http\Requests\CurrentReadingRequest;
use App\Api\Http\Requests\DeleteBookFromCompilationRequest;
use App\Api\Http\Requests\DeleteBookFromUsersListRequst;
use App\Api\Http\Requests\GetBooksRequest;
use App\Api\Http\Requests\GetIdRequest;
use App\Api\Http\Requests\SaveBookRequest;
use App\Api\Http\Requests\SaveBookToCompilationRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\BookAnchor;
use App\Models\BookCompilation;
use App\Models\BookUser;
use App\Models\Compilation;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{

    public function show(GetBooksRequest $request)
    {
        $perPage = $request->showType === Book::SHOW_TYPE_BLOCK ? Book::PER_PAGE_BLOCKS : Book::PER_PAGE_LIST;
        $viewTypeList = $request->showType === Book::SHOW_TYPE_LIST;

        $bookModel = new Book();

        $books = $bookModel->getBook()
            ->when($viewTypeList, function ($query) {

                return $query->withCount(['bookLikes', 'bookComments'])
                    ->with(['year', 'publishers',])
                    ->addSelect('text');
            })
            ->when($request->findByAuthor, function ($query) use ($request) {

                return $query->whereHas('authors', function ($query) use ($request) {
                    $query->where('author', 'like', '%' . $request->findByAuthor . '%');
                });
            })
            ->when(($request->alphabetAuthorIndex && !$request->findByAuthor), function ($query) use ($request) {

                return $query->whereHas('authors', function ($query) use ($request) {
                    $query->where('author', 'like', $request->alphabetAuthorIndex . '%');
                });
            })
            ->when($request->findByPublisher, function ($query) use ($request) {

                return $query->whereHas('publishers', function ($query) use ($request) {
                    $query->where('publisher', 'like', '%' . $request->findByPublisher . '%');
                });
            })
            ->when(($request->alphabetPublisherIndex && !$request->findByPublisher), function ($query) use ($request) {

                return $query->whereHas('publishers', function ($query) use ($request) {
                    $query->where('publisher', 'like', $request->alphabetPublisherIndex . '%');
                });
            })
            ->when($request->findByTitle, function ($query) use ($request) {

                return $query->where('title', 'like', '%' . $request->findByTitle . '%');
            })
            ->when(($request->alphabetTitleIndex && !$request->findByTitle), function ($query) use ($request) {
                $query->where('title', 'like', $request->alphabetTitleIndex . '%');
            })
            ->when($request->sortBy === Book::SORT_BY_DATE, function ($query) {
                return $query->newest();
            })
            ->when($request->sortBy === Book::SORT_BY_RATING, function ($query) {
                return $query->orderBy('rates_avg', 'desc');
            })
            ->when($request->sortBy === Book::SORT_BY_READERS_COUNT, function ($query) {
                return $query->whereHas('bookStatuses', function ($query) {
                    return $query->reading();
                })->withCount('bookStatuses as readersCount')->orderBy('readersCount', 'desc');
            })
            ->when($request->sortBy === Book::SORT_BY_ALPHABET, function ($query) {

                return $query->orderBy('title');
            })
            ->paginate($perPage);

        $collection = $books->getCollection();
        foreach ($collection as &$book) {
            if ($book->rates_avg === null) {
                $book->rates_avg = 0;
            }

            foreach ($book->authors as $author) {
                unset($author->pivot);
            }

            foreach ($book->publishers as $publisher) {
                unset($publisher->pivot);
            }

            foreach ($book->bookGenres as $genres) {
                unset($genres->pivot);
            }
        }
        $books->setCollection($collection);

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function showSingle(GetIdRequest $request)
    {
        $id = $request->id;
        $books = Book::with([
            'authors',
            'image',
            'bookGenres',
            'year',
            'publishers',
            'bookComments',
            'reviews',
            'quotes'])
            ->where('id', $id)
            ->select('id', 'title', 'text')
            ->withCount(['rates', 'bookLikes', 'bookComments', 'reviews', 'quotes'])
            ->withAvg('rates as rates_avg', 'rates.rating')
            ->firstOrFail();

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

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function saveBookToUsersList(SaveBookRequest $request, BookUser $bookUser)
    {

        $user = Auth::user();
        $bookUser->saveBook($user->id, $request->book_id, $request->status);

        return ApiAnswerService::successfulAnswerWithData($bookUser);

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

    public function changeBookStatus(ChangeBookStatusRequest $request, BookUser $bookUser)
    {
        $user = Auth::user();
        $isUsersBook = $user->bookStatuses()->wherePivot('book_id', $request->book_id)->exists();

        if ($isUsersBook) {
            $bookUser->changeStatus($user->id, $request->book_id, $request->status);
            return ApiAnswerService::successfulAnswerWithData($bookUser);
        }
        return ApiAnswerService::errorAnswer("Недостаточно прав", Response::HTTP_FORBIDDEN);

    }

    public function readBook(CurrentReadingRequest $request, Book $book)
    {
        $currentReading = $book->currentReading($request);
        return ApiAnswerService::successfulAnswerWithData($currentReading);
    }

    public function showBookContents(BooksChapterValidation $requset, BookAnchor $book)
    {
        return ApiAnswerService::successfulAnswerWithData($book->bookContents($requset->id));
    }

}
