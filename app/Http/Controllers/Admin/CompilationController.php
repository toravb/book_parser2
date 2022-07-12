<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\CompilationFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Requests\Admin\StoreCompilationRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompilationRequest;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\Compilation;

class CompilationController extends Controller
{
    public function index(Compilation $compilation, CompilationFilter $filter)
    {
        $compilations = $compilation->compilationsForAdmin()->filter($filter)->get();
        return view('admin.compilations.index', compact('compilations'));
    }

    public function create()
    {
        return view('admin.compilations.create');
    }

    public function booksToAdd(Compilation $compilation, Book $book)
    {

    }

    public function storeBooksInCompilation(Compilation $compilation, Book $book, BookCompilation $bookCompilation)
    {
        $bookCompilation->saveBookToCompilation();
    }

    public function store(StoreCompilationRequest $request, Compilation $compilation)
    {
        $compilation->saveFromRequest($request);
        return redirect()->route('admin.compilations.edit', $compilation)->with('success', 'Подборка успешно создана!');
    }

    public function show($id)
    {
    }

    public function edit($compilation, Compilation $compilations)
    {
        $compilation = $compilations->compilationsForAdmin()->findOrFail($compilation);

        return view('admin.compilations.edit', compact('compilation'));

    }

    public function update(UpdateCompilationRequest $request, Compilation $compilation)
    {
        $compilation->saveFromRequest($request);

        return redirect()->route('admin.compilations.edit', $compilation)->with('success', 'Подборка успешно обновлена!');
    }

    public function destroy(Compilation $compilation)
    {
        $compilation->delete();

        return ApiAnswerService::redirect(route('admin.compilations.index'));
    }
}
