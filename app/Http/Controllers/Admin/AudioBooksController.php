<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\AudioBookFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAudioBookRequest;
use App\Http\Requests\UpdateAudioBookRequest;
use App\Models\AudioBook;
use App\Models\Image;

class AudioBooksController extends Controller
{
    public function index(AudioBook $audioBooks, AudioBookFilter $filter)
    {
        $audioBooks = $audioBooks->getForAdmin()->filter($filter)->paginate(25)->withQueryString();

        if(request()->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($audioBooks);
        }

        return view('admin.audiobooks.index', compact('audioBooks'));
    }

    public function create()
    {
        return view('admin.audiobooks.create');
    }

    public function edit($audioBook)
    {
        $audioBook = (new AudioBook())->getForAdmin()
            ->addSelect([
                'audio_books.meta_description',
                'audio_books.meta_keywords',
                'audio_books.alias_url',
            ])
            ->findOrFail($audioBook);

        return view('admin.audiobooks.edit', compact('audioBook'));
    }

    public function store(StoreAudioBookRequest $request, AudioBook $audioBook)
    {
        $audioBook->saveFromRequest($request);

        return redirect()->route('admin.audio-books.edit', $audioBook)->with('success', 'Аудио книга успешно добавлена!');
    }

    public function update(UpdateAudioBookRequest $request, AudioBook $audioBook)
    {
        $audioBook->saveFromRequest($request);

        return redirect()->route('admin.audio-books.edit', $audioBook)->with('success', 'Аудио книга успешно обновлена!');
    }

    public function destroy(AudioBook $audioBook)
    {
        $audioBook->delete();

        return ApiAnswerService::redirect(route('admin.audio-books.index'));
    }
}
