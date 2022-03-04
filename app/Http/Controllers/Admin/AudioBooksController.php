<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;

class AudioBooksController extends Controller
{
    public function index(AudioBook $audioBook)
    {
        $audioBooks = $audioBook->getForAdmin()->paginate(10);

        return view('admin.audio.index', compact('audioBooks'));
    }

    public function create()
    {
        return view('admin.audio.create');
    }

    public function edit()
    {

    }

    public function destroy(AudioBook $audioBook)
    {
        $audioBook->delete();

        return ApiAnswerService::successfulAnswer();
    }
}
