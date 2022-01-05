<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\GetBooksRequest;
use App\Api\Http\Requests\SaveBookRequest;
use App\Http\Controllers\Controller;
use App\Models\BookUser;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class BookController extends Controller
{
    const PER_PAGE_BLOCKS = 40;
    const PER_PAGE_LIST = 13;
    const SHOW_TYPE_BLOCK = 'block';
    const SHOW_TYPE_LIST = 'list';

    public function show(GetBooksRequest $request)
    {
        $perPage = $request->showType === self::SHOW_TYPE_BLOCK ? self::PER_PAGE_BLOCKS : self::PER_PAGE_LIST;
        $viewTypeList = $request->showType === self::SHOW_TYPE_LIST;

        $books = Book::with([
            'authors',
            'image',
            'bookGenres',
//            'bookStatuses'
        ])
            ->select('id', 'title')
            ->withCount('rates')
            ->withAvg('rates as rates_avg', 'rates.rating')
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
            ->when($request->findByPublisher, function ($query) use ($request) {

                return $query->whereHas('publishers', function ($query) use ($request) {
                    $query->where('publisher', 'like', '%' . $request->findByPublisher . '%');
                });
            })
            ->when($request->findByTitle, function ($query) use ($request) {

                return $query->where('title', 'like', '%' . $request->findByTitle . '%');
            })
            ->when($request->sortByDate, function ($query) {
                return Book::newest();
            })
            ->when($request->sortByRating, function ($query) {
                return $query->orderBy('rates_avg', 'desc');
//                    Rate::popular();
            })
            ->when($request->sortByReaders, function ($query) {
                return $query->whereHas('bookStatuses', function ($query) {
                    BookUser::reading();
                })->withCount('bookStatuses as readersCount')->orderBy('readersCount', 'desc');
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
        }
        $books->setCollection($collection);


        return response()->json([
            'status' => 'success',
            'title' => $books
        ]);
    }

    public
    function showSingle(Request $request)
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
            ->first();

        if ($books->rates_avg === null) {
            $books->rates_avg = 0;
        }
        foreach ($books->authors as $author) {
            unset($author->pivot);
        }
        return response()->json([
            'status' => 'success',
            'title' => $books
        ]);
    }

    public
    function saveBook(SaveBookRequest $request, BookUser $bookUser)
    {

        $user = Auth::user();

        $bookUser->user_id = $user->id;
        $bookUser->book_id = $request->book_id;
        $bookUser->status = $request->status;
        $bookUser->save();

        return redirect(route('showSingle'));
    }
}
