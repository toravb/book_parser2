<?php

namespace App\Http\Controllers\Admin;

use App\Api\Http\Controllers\AudioBookController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Models\AudioBook;
use App\Models\Book;
use App\Models\Image;
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
        return view('admin.books.index', ['books' => $books]);

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

    public function createBook()
    {

        return view('admin.books.create');
    }

    public function storeBook(StoreBookRequest $request, Book $book, Image $cover)
    {

        dd($request->all());
        $background = $request->file('cover-image')->store('BookCoverImages');
        $bookFile = $request->file('book-file')->store('Books');
//        dd($request->status);
//        dd((int)$request->get('status'));
        $bookId = $book->storeBooksByAdmin(
            $request->title,
            $request->description,
            $request->status,
            $bookFile,
        );

        $cover->storeBookCoverByAdmin($bookId, $background);
        return redirect(route('admin.book.create'));

    }

    public function getAudioBookForDasboard(AudioBook $audioBook)
    {
        $audioBooks = $audioBook->getForAdmin()->get();
//dd($audioBooks);
        return view('admin.audio.index', ['audioBooks' => $audioBooks]);
    }

    public function createAudio()
    {
        return view('admin.audio.create');
    }

    public function storeAudio ()
    {
        dd(\request());
    }

}
