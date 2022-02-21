<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookGenre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.books');
    }

    public function listBooks(Book $book)
    {
        $books = $book->getBooksForAdminPanel()->paginate(10);
//        dd($books);
        return view('admin.home.books', ['books' => $books]);

    }

    public function edit($book)
    {
        $book =  (new Book())->getBooksForAdminPanel()
            ->findOrFail($book);

        return view('admin.home.edit', compact('book'));
    }
    public function storeEdit(Book $book)
    {
        $book->update(\request()->only(['id', 'title', 'text']));
    }
    public function create(){

        return view('admin.books.create');
    }
    public function store(Request $request){

        dd($request);
//        $book->title=$request->title;
//        $book->text=$request->text;
       // $book->save();

        return redirect()->back()->withSuccess('Книга была успешно добавленна');
    }
}
