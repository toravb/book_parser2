<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\GenresFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;

class GenresController extends Controller
{
    public function index(Genre $genres, GenresFilter $filter)
    {
        $genres = $genres->filter($filter)->paginate(25)->withQueryString();

        return view('admin.genres.index', compact('genres'));
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function store(StoreGenreRequest $request, Genre $genre)
    {
        $genre->saveFromRequest($request);

        return redirect()->route('admin.genres.edit', $genre)->with('success', 'Категория успешно создана!');
    }

    public function update(Genre $genre, UpdateGenreRequest $request)
    {
        $genre->saveFromRequest($request);

        return redirect()->route('admin.genres.edit', $genre)->with('success', 'Категория успешно обновлена!');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return ApiAnswerService::redirect(route('admin.genres.index'));
    }
}
