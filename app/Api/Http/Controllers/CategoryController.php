<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookGenre;


class CategoryController extends Controller
{
    public function show()
    {
        $genres = BookGenre::orderBy('name')->get();
        return response()->json([
                'status' => 'success',
                'data' => $genres
            ]
        );
    }
}
