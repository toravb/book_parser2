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

    const PER_PAGE_LIST = 13;

    const SORT_BY_DATE = '1';
    const SORT_BY_RATING = '2';
    const SORT_BY_ALPHABET = '3';


    public function showBooks(GetUsersBooksRequest $request, Book $book)
    {

        $this->user = Auth::user();
        $bookModel = $book->getBook()
            ->whereHas('users', function (Builder $query) use ($request) {
                return  $query->where('user_id', $this->user->id)
                    ->when($request->status, function (Builder $query) use ($request)  {
                        return   $query->where('status', $request->status);
                    });

            })
            ->when($request->sortBy === self::SORT_BY_DATE, function ($query) {
                return $query->newest();
            })
            ->when($request->sortBy === self::SORT_BY_RATING, function ($query) {
                return $query->popular();

            })
            ->when($request->sortBy === self::SORT_BY_ALPHABET, function ($query) {

                return $query->orderBy('title');
            })
            ->paginate(self::PER_PAGE_LIST);

        return ApiAnswerService::successfulAnswerWithData($bookModel);
    }


}
