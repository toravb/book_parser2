<?php

namespace App\api\Http\Controllers;


use App\Api\Http\Requests\GetBooksRequest;
use App\Api\Http\Requests\GetUsersBooksRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class UsersBooksController extends Controller
{

    public function showBooks(GetUsersBooksRequest $request, Book $book)
    {

        $this->user = Auth::user();
        $bookModel = $book->getBook()
            ->when($request->search, function ($query) use ($request) {

                return $query->whereHas('authors', function ($query) use ($request) {
                    $query->where('author', 'like', '%' . $request->search . '%');
                }) -> orWhere ('title', 'like', '%' . $request->search . '%');
            })

            ->whereHas('users', function (Builder $query) use ($request) {
                return  $query->where('user_id', $this->user->id)
                    ->when($request->status, function (Builder $query) use ($request)  {
                        return   $query->where('status', $request->status);
                    });

            })
            ->when($request->sortBy === Book::SORT_BY_DATE, function ($query) {
                return $query->newest();
            })
            ->when($request->sortBy === Book::SORT_BY_RATING, function ($query) {
                return $query->popular();

            })
            ->when($request->sortBy === Book::SORT_BY_ALPHABET, function ($query) {

                return $query->orderBy('title');
            })
            ->paginate(Book::PER_PAGE_LIST);

        return ApiAnswerService::successfulAnswerWithData($bookModel);
    }


}
