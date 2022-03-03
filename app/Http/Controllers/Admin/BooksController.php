<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Models\Book;
use App\Models\Image;

class BooksController extends Controller
{
    public function index(Book $book)
    {
        $books = $book->getBooksForAdminPanel()->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function edit($book)
    {
        $book = (new Book())->getBooksForAdminPanel()->findOrFail($book);

        echo 'переработать редактирование книги.';
        dd($book);
        return view('admin.books.edit', compact('book'));
    }

    public function store(StoreBookRequest $request, Book $book, Image $cover)
    {
        dd($request->all());
        $background = $request->file('cover-image')->store('BookCoverImages');
        $bookFile = $request->file('book-file')->store('Books');
        $bookId = $book->storeBooksByAdmin(
            $request->title,
            $request->description,
            $request->status,
            $bookFile,
        );

        $cover->storeBookCoverByAdmin($bookId, $background);
        return redirect(route('admin.book.create'));

    }

    public function update(Book $book)
    {
        $book->update(\request()->only(['id', 'title', 'text']));
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return ApiAnswerService::successfulAnswer();
    }
}
