<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\BookFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Compilation;

class MainPageNoveltiesCompilationController extends Controller
{
    public function index(Compilation $compilation, Book $book, BookFilter $filter)
    {
        if (!$compilation->where('location', Compilation::NOVELTIES_LOCATION)->exists()) {
            $compilation->createMainPageAdminCompilation(Compilation::NOVELTIES_LOCATION);
        }

        $books = $book->bookForNoveltiesMainPageCompilation()->filter($filter)->paginate(25)->withQueryString();

        return view('admin.compilations.main_page_new_books.index', compact('books'));
    }

    public function edit($bookID, Compilation $compilation)
    {
        $compilation->addBookToAdminCompilation(
            $bookID,
            (new Book())->type,
            Compilation::NOVELTIES_LOCATION
        );

        return redirect(route('admin.compilations.novelties.add.books'));
    }

    public function showBooks(Book $books, BookFilter $filter)
    {
        $books = $books
            ->dataForNoveltiesCompilation()
            ->filter($filter)
            ->paginate(25)
            ->withQueryString();

        if (request()->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($books);
        }

        return view('admin.compilations.main_page_new_books.add-books', compact('books'));
    }

    public function destroy($book, Compilation $compilation)
    {
        $compilation->removeBookFromAdminCompilation($book, Compilation::NOVELTIES_LOCATION);

        return ApiAnswerService::redirect(route('admin.compilations.novelties.index'));
    }
}
