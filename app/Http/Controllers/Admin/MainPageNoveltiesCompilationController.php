<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\Compilation;
use Illuminate\Http\Request;

class MainPageNoveltiesCompilationController extends Controller
{
    public function index(Compilation $compilation)
    {
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
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
