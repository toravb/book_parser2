<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Image;

class BooksController extends Controller
{
    public function index(Book $books)
    {
        $books = $books->dataForAdminPanel()->paginate(25);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function edit($book, Book $books)
    {
        $book = $books->dataForAdminPanel()
            ->addSelect([
                'meta_description',
                'meta_keywords',
                'alias_url',
                'text',
            ])
            ->findOrFail($book);

        return view('admin.books.edit', compact('book'));
    }

    public function store(StoreBookRequest $request, Book $book)
    {
        $book->saveFromRequest($request);

        return redirect()->route('admin.books.edit', $book)->with('success', 'Книга успешно создана!');
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->saveFromRequest($request);

        return redirect()->route('admin.books.edit', $book)->with('success', 'Книга успешно обновлена!');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return ApiAnswerService::redirect(route('admin.books.index'));
    }
}
