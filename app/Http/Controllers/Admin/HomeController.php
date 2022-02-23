<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Models\Book;
use App\Models\BookGenre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
    }

    public function listBooks(Book $book)
    {
        $books = $book->getBooksForAdminPanel()->paginate(10);
//        dd($books);
        return view('admin.home.books', ['books' => $books]);

    }

    public function edit($book)
    {
        $book = (new Book())->getBooksForAdminPanel()
            ->findOrFail($book);

        return view('admin.home.edit', compact('book'));
    }

    public function storeEdit(Book $book)
    {
        $book->update(\request()->only(['id', 'title', 'text']));
    }

    public function create()
    {

        return view('admin.books.create');
    }

    public function store(StoreBookRequest $request, Book $book)
    {

        $background = $request->file('cover-image')->store('BookCoverImages');
        $bookFile = $request->file('book-file')->store('Books');
//        dd($request->all());
//        dd($request->status);
//        dd((int)$request->get('status'));
        $book->storeBooksByAdmin(
            $request->title,
            $request->description,
            $request->status,
            $bookFile,
        );
    }
}
