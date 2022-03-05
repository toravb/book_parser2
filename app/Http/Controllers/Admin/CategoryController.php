<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Genre $genre)
    {
        $genre->firstOrCreate(['name' => request()->name]);

        return redirect(route('admin.categories.index'));
    }

    public function index(Genre $category)
    {
        $categories = $category->index();

        return view('admin.categories.index', compact('categories'));
    }

    public function edit ($category)
    {
//        dd($category);
        //TODO: заменить полсе переопределение (объединение) таблицы жанров
        $category = (new Genre())->findOrFail($category);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateGenreRequest $request, Genre $genre)
    {
//        dd($request);
       $genre->storeUpdates($request->id ,$request->genre);
       return redirect(route('admin.categories.index'));
    }

    public function destroy($genre)
    {
        dd(1);
    }

}
