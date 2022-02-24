<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;

class CategoryController extends Controller
{
    public function index(Genre $category)
    {
        $categories = $category->index();

        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function edit ($category)
    {
//        dd($category);
        //TODO: заменить полсе переопределение (объединение) таблицы жанров
        $category = (new Genre())->findOrFail($category);

        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(UpdateGenreRequest $request, BookGenre $genre)
    {
//        dd($request);
       $genre->storeUpdates($request->id ,$request->genre);
       return redirect(route('admin.category.index'));
    }

}
