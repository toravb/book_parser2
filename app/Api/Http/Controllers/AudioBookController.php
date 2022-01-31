<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\View;
use Illuminate\Http\Request;

class AudioBookController extends Controller
{

    public function showAudioBookDetails($id, AudioBook $book, View $view, Request $request)
    {
        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $book->getTypeAttribute());
    }

}
