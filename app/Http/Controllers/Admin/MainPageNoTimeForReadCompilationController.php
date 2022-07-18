<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\AudioBookFilter;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\Compilation;
use App\Models\AudioBook;

class MainPageNoTimeForReadCompilationController extends Controller
{
    public function index(AudioBook $audioBook, Compilation $compilation, AudioBookFilter $filter)
    {
        if (!$compilation->where('location', Compilation::NO_TIME_FOR_READ_LOCATION)->exists()) {
            $compilation->createMainPageAdminCompilation(Compilation::NO_TIME_FOR_READ_LOCATION);
        }

        $audiobooks = $audioBook->getAudioBooksForMainPageComp()->filter($filter)->paginate(25)->withQueryString();

        return view('admin.compilations.no_time_for_read.index', compact('audiobooks'));
    }

    public function edit($audiobook, Compilation $compilation)
    {
        $compilation->addBookToAdminCompilation(
            $audiobook,
            (new AudioBook)->type,
            Compilation::NO_TIME_FOR_READ_LOCATION,
        );

        return redirect(route('admin.compilations.no-time-for-read.add.audiobooks'));
    }

    public function showAudiobooks(AudioBook $audioBooks, AudioBookFilter $filter)
    {
        $audioBooks = $audioBooks->dataForNoTimeToReadCompilation()->filter($filter)->paginate(25)->withQueryString();

        if (request()->ajax()) {
            return ApiAnswerService::successfulAnswerWithData($audioBooks);
        }

        return view('admin.compilations.no_time_for_read.add-books', compact('audioBooks'));
    }

    public function destroy($audiobook, Compilation $compilation)
    {
        $compilation->removeBookFromAdminCompilation($audiobook,Compilation::NO_TIME_FOR_READ_LOCATION);

        return ApiAnswerService::redirect(route('admin.compilations.no-time-for-read.index'));
    }
}
