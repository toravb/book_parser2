<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudioBook;

class AudioBooksController extends Controller
{
    public function index(AudioBook $audioBook)
    {
        $audioBooks = $audioBook->getForAdmin()->get();
        return view('admin.audio.index', ['audioBooks' => $audioBooks]);
    }

    public function create()
    {
        return view('admin.audio.create');
    }

    public function edit()
    {

    }
}
