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
use App\Api\Http\Requests\GetByLetterRequest;
use App\Api\Http\Requests\NoveltiesRequest;
use App\Api\Http\Requests\SaveBookToCompilationRequest;
use App\Api\Http\Requests\ShowBooksFilterByLetterRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShowBooksInUsersListRequest;
use App\Models\AudioBook;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\BookUser;
use App\Models\Compilation;
use App\Models\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    const NOVELTIES_PAGINATE = 32;
    const MY_BOOK_LIST_QUANTITY = 12;

    public function setReaders(){
        $book = new(Book::class);
        $book->setReaders($book);
        $audioBook = new(AudioBook::class);
        $audioBook->setListeners($audioBook);
    }

    public function show(GetBooksRequest $request, BookFilter $bookFilter, AudioBookFilter $audioBookFilter, BookFactory $bookFactory)
    {
        $perPageList = $request->type === QueryFilter::TYPE_BOOK ? QueryFilter::PER_PAGE_LIST : QueryFilter::PER_PAGE_LIST_AUDIO;

        $perPage = $request->showType === QueryFilter::SHOW_TYPE_BLOCK ? QueryFilter::PER_PAGE_BLOCKS : $perPageList;

        $model = $bookFactory->createInstance($request->type);
        $books = $model->getBook()->filter($model instanceof Book ? $bookFilter : $audioBookFilter)
            ->paginate($perPage);

        $collection = $books->getCollection();
        foreach ($collection as &$book) {

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

    public function changeBookStatus(ChangeBookStatusRequest $request, BookUser $bookUser): JsonResponse
    {
        $user = Auth::user()->id;

        $bookUser->changeCreateStatus($user, $request->book_id, $request->status);

        return ApiAnswerService::successfulAnswerWithData($bookUser);
    }

    public function readBook(CurrentReadingRequest $request, Book $book): JsonResponse
    {
        $pageNumber = $request->pageNumber ? $request->pageNumber : 1;

        $currentReading = $book->currentReading($request, $pageNumber);

        $currentReading->reading_progress = round(($pageNumber / $currentReading->pages_count) * 100);

        return ApiAnswerService::successfulAnswerWithData($currentReading);
    }

    public function showByLetter(GetByLetterRequest $request, Book $books): JsonResponse
    {
        $books = $books->select(['id', 'title'])
            ->where('title', 'like', $request->letter . '%')->paginate(300);

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function getBookmarks(Book $book): JsonResponse
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
                ->addSelect('chapters.id')
                ->first();
        }

        return ApiAnswerService::successfulAnswerWithData($bookmarks);
    }

    public function showUserBooks(ShowBooksInUsersListRequest $request, BookFilter $bookFilter): JsonResponse
    {
        $books = \auth()->user()
            ->bookStatuses()
            ->filter($bookFilter)
            ->where('active', true)
            ->addSelect(['status', 'book_user.created_at'])
            ->with([
                'authors:id,author',
                'image:book_id,link',
                'bookGenres:id,name',
            ])
            ->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->paginate(
                self::MY_BOOK_LIST_QUANTITY,
                ['id', 'title']
            );

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function filteringByLetterPage(
        ShowBooksFilterByLetterRequest $request,
        AudioBookFilter                $audioBookFilter,
        BookFilter                     $bookFilter,
        BookFactory                    $bookFactory
    )
    {
        $model = $bookFactory->createInstance($request->type);
        $books = $model
            ->getBookForLetterFilter()
            ->filter($model instanceof Book ? $bookFilter : $audioBookFilter)
            ->paginate(300);

        return ApiAnswerService::successfulAnswerWithData($books);
    }

    public function novelties(
        NoveltiesRequest $request,
        Book             $books,
        AudioBook        $audioBooks,
        BookFactory      $bookFactory,
        BookFilter       $bookFilter,
        AudioBookFilter  $audioBookFilter
    )
    {

        if ($request->type === QueryFilter::TYPE_ALL) {

            $newBooks = $books
                ->select('books.id', 'books.created_at')
                ->selectRaw("coalesce('books', '0') as 'type'")
                ->when(\request()->sortBy === QueryFilter::BESTSELLERS, function ($query) {
                    $query->withAvg('rates as rates_avg', 'rates.rating');
                });

            $newAudioBooks = $audioBooks
                ->select('audio_books.id', 'audio_books.created_at')
                ->selectRaw("coalesce('audioBooks', '0') as 'type'")
                ->when(\request()->sortBy === QueryFilter::BESTSELLERS, function ($query) {
                    $query->withAvg('rates as rates_avg', 'rates.rating');
                });

            $novelties = $newBooks->unionAll($newAudioBooks)
                ->when($request->sortBy === QueryFilter::SORT_BY_DATE, function (Builder $query) {
                    $query->latest();
                })
                ->when($request->sortBy === QueryFilter::BESTSELLERS, function (Builder $query) {
                    $query->orderBy('rates_avg', 'desc');
                })
                ->paginate(self::NOVELTIES_PAGINATE);

            $allBooks = collect();

            $allBooks = $allBooks->concat((new Book())->noveltiesBooks()->whereIn('books.id', $novelties->filter(function ($value) {
                return $value->type === QueryFilter::TYPE_BOOK;
            })->map(function ($value) {
                return $value->id;
            }))->get());

            $allBooks = $allBooks->concat((new AudioBook())->noveltiesBooks()->whereIn('audio_books.id', $novelties->filter(function ($value) {
                return $value->type === QueryFilter::TYPE_AUDIO_BOOK;
            })->map(function ($value) {
                return $value->id;
            }))->get());

            $newNovelties = collect();
            foreach ($novelties as $novelty) {
                $newNovelties->add($allBooks->first(function ($value) use ($novelty) {
                    return $novelty->type === $value->type and $novelty->id === $value->id;
                }));
            }

            $novelties->setCollection($newNovelties);

            return ApiAnswerService::successfulAnswerWithData($novelties);
        } else {

            $model = $bookFactory->createInstance($request->type);
            $novelties = $model->noveltiesBooks()->filter($model instanceof Book ? $bookFilter : $audioBookFilter)
                ->paginate(self::NOVELTIES_PAGINATE);
            return ApiAnswerService::successfulAnswerWithData($novelties);
        }

    }

    public function getSimilarBooks(Book $book): JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($book->getSimilarBooks());
    }
}
