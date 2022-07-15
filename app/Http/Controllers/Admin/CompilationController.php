<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\AudioBookFilter;
use App\Admin\Filters\BookFilter;
use App\Admin\Filters\CompilationFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Requests\Admin\StoreCompilationRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompilationRequest;
use App\Models\AudioBook;
use App\Models\Book;
use App\Models\BookCompilation;
use App\Models\Compilation;
use Illuminate\Http\Request;

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

    public function booksToAdd(Compilation $compilation, Book $book, BookFilter $bookFilter)
    {
        $books = $book
            ->booksForAddToAdminCompilations($compilation->id)
            ->filter($bookFilter)
            ->paginate(25);
        return view('admin.compilations.add-books', compact(['books', 'compilation']));
    }

    public function audiobooksToAdd(Compilation $compilation, AudioBook $audioBook, AudioBookFilter $audioBookFilter,)
    {
        $books = $audioBook
            ->booksForAddToAdminCompilations($compilation->id)
            ->filter($audioBookFilter)
            ->paginate(25);
        return view('admin.compilations.add-books', compact(['books', 'compilation']));
    }

    public function storeBooksInCompilation(
        Request         $request,
        Compilation     $compilation,
                        $book,
        BookCompilation $bookCompilation,

    )
    {
        $bookCompilation->saveBookToCompilation($compilation->id, $book, $request->type);
        if ($request->type == "books") {
            $route = 'admin.compilations.add-books';
        }
        if ($request->type == "audioBooks") {
            $route = 'admin.compilations.add-audiobooks';
        }

        return redirect(route($route, compact('compilation')));
    }

    public function store(StoreCompilationRequest $request, Compilation $compilation)
    {
        $compilation->saveFromRequest($request);
        return redirect()->route('admin.compilations.edit', $compilation)->with('success', 'Подборка успешно создана!');
    }

    public function show(Compilation $compilation)
    {
        $compilation->adminCompilationWithBooks();

        return view('admin.compilations.show', compact('compilation'));
    }

    public function edit(Compilation $compilation)
    {
        $compilation = $compilation->compilationsForAdmin()->findOrFail($compilation->id);

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

    public function removeBookFromCompilation(Compilation $compilation, $id, $type)
    {
        (new BookCompilation())->deleteBookFromCompilation($compilation->id, $id, $type);

        return ApiAnswerService::redirect(route('admin.compilations.show', compact('compilation')));
    }
}
