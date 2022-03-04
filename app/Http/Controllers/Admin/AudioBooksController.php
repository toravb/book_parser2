<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\Image;

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

    public function store(AudioBook $audioBook, Image $cover)
    {
        dd(request()->all());
        $background = $request->file('cover-image')->store('AudioBookCoverImages');
        $bookFile = $request->file('audio-file')->store('AudioBooks');
        $audioBookId = $audioBook->storeAudioBooksByAdmin(
            $request->title,
            $request->description,
            $request->status,
            $bookFile,
        );

        $cover->storeAudioBookCoverByAdmin($audioBookId, $background);
        return redirect(route('admin.audio_book.create'));

    }

    public function destroy(AudioBook $audioBook)
    {
        $audioBook->delete();

        return ApiAnswerService::successfulAnswer();
    }
}
