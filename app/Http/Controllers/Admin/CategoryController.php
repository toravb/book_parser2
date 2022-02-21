<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookGenre;

class CategoryController extends Controller
{
    public function index(BookGenre $category)
    {
        $categories = $category->index();

        return view('admin.categories.index', ['categories' => $categories]);
    }

}
