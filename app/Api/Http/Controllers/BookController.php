<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Matrix\Builder;

class BookController extends Controller
{
    const PER_PAGE_BLOCKS = 40;
    const PER_PAGE_LIST = 13;
    const SHOW_TYPE_BLOCK = 'block';
    const SHOW_TYPE_LIST = 'list';

    public function show(Request $request)
    {
        $perPage = $request->showType === self::SHOW_TYPE_BLOCK ? self::PER_PAGE_BLOCKS : self::PER_PAGE_LIST;
        $viewTypeList = $request->showType === self::SHOW_TYPE_LIST;

        $book = Book::with([
            'authors' ,
            'image',
            'bookGenres'])
            ->select('id', 'title')
            ->withCount('rates')
            ->withAvg('rates', 'rates.rating')
            ->when($viewTypeList, function ($query) {

                return $query->withCount(['bookLikes', 'bookComments'])
                    ->with(['year', 'publishers',])
                    ->addSelect('text');
            })
            ->when($request->findByAuthor, function ($query) use($request){

                return $query->whereHas('authors', function ($query) use($request){
                    $query->where('author', 'like', '%'.$request->findByAuthor.'%');
                });
            })
//            ->when($request->findByTitle, function ($query) use($request){
//
//                return $query->where('title', 'like', '%$request->findByTitle%');
//            })
//            ->when($request->findByPublisher, function ($query) use($request){
//
//                return $query->where('publishers', 'like', '%$request->findByPublisher%');
//            })

//            ->dd()
            ->paginate($perPage);


        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }
}
