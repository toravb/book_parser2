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
use Illuminate\Http\JsonResponse;
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

    public function chapters(AudioBook $audiobook): JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($audiobook->audioBookChapters());
    }

    public function similar(AudioBook $audiobook): JsonResponse
    {
        return ApiAnswerService::successfulAnswerWithData($audiobook->getSimilarAudioBooks());
    }

    public function changeCreateStatus(CreateChangeAudioBookStatusRequest $request, AudioBookUser $audioBookUser): JsonResponse
    {
        $audioBookUser->createChangeStatus(\auth()->id(), $request->audio_book_id, $request->status);

        return ApiAnswerService::successfulAnswerWithData($audioBookUser);
    }

    public function deleteAudioBookFromUsersList(DeleteAudioBookFromUsersListRequest $request, AudioBookUser $audioBookUser): JsonResponse
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
        $user = Auth::user();

        $audiobooks = $user
            ->audioBookStatuses()
            ->where('active', true)
            ->addSelect('status')
            ->with([
                'authors:id,author',
                'image:book_id,link',
                'genre:id,name',
            ])->withCount('views')
            ->withAggregate('rates as rates_avg', 'Coalesce( Avg( rates.rating ), 0 )')
            ->filter($filter)
            ->paginate(
                self::AUDIOBOOK_USER_QUANTITY,
                ['id', 'title', 'genre_id']
            );

        return ApiAnswerService::successfulAnswerWithData($audiobooks);
    }
}
