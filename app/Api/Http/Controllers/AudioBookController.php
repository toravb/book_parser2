<?php

namespace App\Api\Http\Controllers;

use App\Api\Filters\AudioBookFilter;
use App\Api\Http\Requests\CreateChangeAudioBookStatusRequest;
use App\Api\Http\Requests\DeleteAudioBookFromUsersListRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShowAudioBooksUserHasRequest;
use App\Models\AudioBook;
use App\Models\AudioBookUser;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AudioBookController extends Controller
{
    const AUDIOBOOK_USER_QUANTITY = 12;

    public function showAudioBookDetails($id, AudioBook $book, View $view, Request $request)
    {
        $audioBook = $book->showAudioBookDetails($id);
        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $book->getTypeAttribute());

        if ($audioBook->rates_avg === null) {
            $audioBook->rates_avg = 0;
        }

        return ApiAnswerService::successfulAnswerWithData($audioBook);
    }

    public function listeningMode(AudioBook $book, View $view, Request $request)
    {
        //TODO: Добавить вывод глав с названиями

    }

    public function changeCreateStatus(CreateChangeAudioBookStatusRequest $request, AudioBookUser $audioBookUser): \Illuminate\Http\JsonResponse
    {
        $audioBookUser->createChangeStatus(\auth()->id(), $request->audio_book_id, $request->status);

        return ApiAnswerService::successfulAnswerWithData($audioBookUser);
    }

    public function deleteAudioBookFromUsersList(DeleteAudioBookFromUsersListRequest $request, AudioBookUser $audioBookUser): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $isUsersBook = $user->audioBookStatuses()->wherePivot('audio_book_id', $request->audio_book_id)->exists();

        if ($isUsersBook) {
            $audioBookUser->deleteFromUsersList($user->id, $request->audio_book_id);
            return ApiAnswerService::successfulAnswerWithData($audioBookUser);

        }

        return ApiAnswerService::errorAnswer("Недостаточно прав для редактирования", Response::HTTP_FORBIDDEN);
    }

    public function showUserAudioBooks(ShowAudioBooksUserHasRequest $request, AudioBookFilter $filter)
    {
        $columns = ['id', 'title', 'genre_id'];

        $audiobooks = \auth()->user()
            ->audioBookStatuses()
            ->where('active', true)
            ->addSelect('status')
            ->with([
                'authors:id,author',
                'image:book_id,link',
                'genre:id,name',
            ])->withCount('views')
            ->withAvg('rates as rates_avg', 'rates.rating')
            ->filter($filter)
            ->paginate(self::AUDIOBOOK_USER_QUANTITY, $columns);

        $audiobooks->map(function ($audiobook) {
            if ($audiobook->rates_avg === null) {
                $audiobook->rates_avg = 0;
            }
        });

        return ApiAnswerService::successfulAnswerWithData($audiobooks);
    }
}
