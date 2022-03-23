<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\PageFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageImageRequest;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Book;
use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(Book $book, PageFilter $filter)
    {
        $pages = $book->pages()->select([
            'id',
            'book_id',
            'page_number',
        ])->filter($filter)->paginate(25)->withQueryString();

        if(\request()->ajax()) {
            return  ApiAnswerService::successfulAnswerWithData($pages);
        }

        return view('admin.pages.index', compact('book', 'pages'));
    }

    public function create(Book $book)
    {
        return view('admin.pages.create', compact('book'));
    }

    public function store(StorePageRequest $request, Page $page)
    {
        $page->saveFromRequest($request);

        return redirect()->route('admin.books.pages.edit', [$page->book_id, $page])->with('success', 'Страница книги успешно создана!');
    }

    public function storeImage(StorePageImageRequest $request)
    {
        $location = \Storage::disk('public')->put('pages', $request->file);

        return response()->json([
           'location' => \Storage::url($location),
        ]);
    }

    public function edit(Book $book, Page $page)
    {
        if(\request()->ajax()) {
            return ApiAnswerService::successfulAnswerWithData(compact('page'));
        }

        return view('admin.pages.edit', compact('book', 'page'));
    }

    public function update(UpdatePageRequest $request, $book_id, Page $page)
    {
        $page->saveFromRequest($request);

        return redirect()->route('admin.books.pages.edit', [$page->book_id, $page])->with('success', 'Страница книги успешно обновлена!');
    }

    public function destroy(Book $book, Page $page)
    {
        $page->delete();

        return ApiAnswerService::redirect(url()->previous());
    }
}
