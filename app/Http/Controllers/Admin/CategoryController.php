<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\BookGenre;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class CategoryController extends Controller
{
    public function index(BookGenre $category)
    {
        $categories = $category->index();

        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function edit ($category)
    {
//        dd($category);
        //TODO: заменить полсе переопределение (объединение) таблицы жанров
        $category = (new BookGenre())->findOrFail($category);

        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(UpdateGenreRequest $request, BookGenre $genre)
    {
//        dd($request);
       $genre->storeUpdates($request->id ,$request->genre);
       return redirect(route('admin.category.index'));
    }

}
