<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
    }

    public function listBooks(Book $book)
    {
        $books = $book->getBooksForMainPageFilter()->paginate(10);
        return view('admin.home.books', ['book' => $books]);

    }
}
