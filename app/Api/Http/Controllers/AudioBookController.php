<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\CreateChangeAudioBookStatusRequest;
use App\Api\Http\Requests\DeleteAudioBookFromUsersListRequest;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Models\AudioBook;
use App\Models\AudioBookUser;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AudioBookController extends Controller
{

    public function showAudioBookDetails($id, AudioBook $book, View $view, Request $request)
    {
        $audioBook = $book->showAudioBookDetails($id);
        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $book->getTypeAttribute());
        return ApiAnswerService::successfulAnswerWithData($audioBook);
    }

    public function listeningMode(AudioBook $book, View $view, Request $request): \Illuminate\Http\JsonResponse
    {

        $view->addView(\auth('api')->user()?->id, $request->ip(), $id, $book->getTypeAttribute());

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


}
