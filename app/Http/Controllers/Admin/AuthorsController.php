<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\AuthorsFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;

class AuthorsController extends Controller
{
    public function index(Author $authors, AuthorsFilter $filter)
    {
        $authors = $authors->select([
            'id',
            'author',
            'avatar'
        ])->filter($filter)->paginate(25)->withQueryString();

        if (request()->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($authors);
        }

        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(StoreAuthorRequest $request, Author $author)
    {
        $author->saveFromRequest($request);

        return redirect()->route('admin.authors.edit', $author)->with('success', 'Автор успешно создан!');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(UpdateAuthorRequest $request, Author $author)
    {
        if ($request->remove_avatar and $author->avatar) {
            \Storage::delete($author->avatar);
            $author->avatar = null;
        }

        $author->saveFromRequest($request);

        return redirect()->route('admin.authors.edit', $author)->with('success', 'Автор успешно обновлён!');
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return ApiAnswerService::redirect(route('admin.authors.index'));
    }
}
