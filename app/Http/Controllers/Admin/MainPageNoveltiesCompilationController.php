<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Compilation;
use Illuminate\Http\Request;

class MainPageNoveltiesCompilationController extends Controller
{
    public function index(Compilation $compilation)
    {
        $books = $compilation
            ->with(['books:id,title,active,year_id', 'books.year:id,year'])
            ->where('location', 1)
            ->get();
//        dd($books);
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

    public function destroy($book)
    {
        $compilations = new Compilation();
        $compilation = $compilations->where('location', 1)->get();
        $compilations->where('compilation_id', $compilation->id)
            ->where('compilationable_id', $book->id)
            ->where('compilationable_type', $book->type)
            ->delete();
        return ApiAnswerService::redirect(route('admin.compilations.novelties.index'));
    }
}
