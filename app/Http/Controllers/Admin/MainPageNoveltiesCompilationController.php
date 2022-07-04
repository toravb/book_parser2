<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\BookFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\Admin\Compilation;
use Illuminate\Http\Request;

class MainPageNoveltiesCompilationController extends Controller
{
    public function index()
    {
        $compilation = new Compilation();

        if((new Compilation())->where('location', 1)->exists()){
            $books = Book::query()
                ->whereHas('compilations', function ($query) {
                    return $query->where('location', '1');
                })
                ->select(['id', 'title', 'active', 'year_id'])
                ->with([
                    'genres:id,name',
                    'authors:id,author',
                    'year:id,year'
                ])
                ->get();
        } else {
            $compilation->storeCompilation(
                '',
                '',
                '',
                auth()->user()->id,
                null,
                1
            );
        }
        $books = Book::query()
            ->whereHas('compilations', function ($query) {
                return $query->where('location', '1');
            })
            ->select(['id', 'title', 'active', 'year_id'])
            ->with([
                'genres:id,name',
                'authors:id,author',
                'year:id,year'
            ])
            ->get();
        return view('admin.compilations.main_page_new_books.index', compact('books'));
    }

    public function addBooksToNoveltiesCompilation($bookID)
    {
        (new Compilation())->addBookToNovelties($bookID);

        return redirect(route('admin.compilations.novelties.books-for-novelties'));
    }

    public function showBooksForAdd(Book $books, BookFilter $filter)
    {
        $books = $books
            ->dataForNoveltiesCompilation()
            ->filter($filter)
            ->paginate(5)
            ->withQueryString();

        return view('admin.compilations.main_page_new_books.add-books', compact('books'));
    }

    public function removeFromNovelties($bookID, Compilation $compilation, BookCompilation $noveltiesCompilation)
    {
        $bookCompilation = $compilation->where('location', 1)->first();

        $noveltiesCompilation->where('compilation_id', $bookCompilation->id)
            ->where('compilationable_id', $bookID)
            ->delete();

        return redirect(route('admin.compilations.novelties.index'));
    }
}
