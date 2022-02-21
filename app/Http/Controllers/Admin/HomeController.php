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
        return view('admin.home.index');
    }

    public function listBooks(Book $book)
    {
        $books = $book->getBooksForMainPageFilter()->paginate(10);
        return view('admin.home.books', ['books' => $books]);

    }

    public function edit(Book $book)
    {
        return view('admin.home.edit', [
            'book'=> $book
        ]);
    }
    public function create( BookGenre $category){
        $categories = $category->index();


        return view('admin.home.create', ['categories' => $categories]);
    }
    public function store(Request $request, Book $book){
        $book->title=$request->title;
        $book->text=$request->text;
       // $book->save();

        return redirect()->back()->withSuccess('Книга была успешно добавленна');
    }
}
