<?php

namespace App\Api\Http\Controllers;

use App\Api\Events\NewNotificationEvent;
use App\Api\Http\Requests\SaveCommentRequest;
use App\Api\Http\Requests\ShowCommentsOnCommentRequest;
use App\Api\Interfaces\Types;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Api\Http\Requests\ShowCommentsRequest;
use App\Models\BookComment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    const COMMENTSPAGINATE = 3;
    const COMMENTSONCOMMENTPAGINATE = 5;

    private array $commentTypes;

    public function __construct(Types $types)
    {
        $this->commentTypes = $types->getCommentTypes();
    }

    public function saveComment(SaveCommentRequest $request)
    {
        $field = $this->getFieldName($request->type);
        $userId = Auth::id();
        $record = $this->commentTypes[$request->type]
            ::create(
                [
                    'user_id' => $userId,
                    $field => $request->id,
                    'content' => $request->text,
                    'parent_comment_id' => $request->parent_comment_id
                ]);
        if ($request->parent_comment_id !== null) {
            NewNotificationEvent::dispatch(NewNotificationEvent::ANSWER_ON_COMMENT_AND_ALSO_COMMENTED, $request->type, $record->id, $userId);
        }

        return ApiAnswerService::successfulAnswerWithData($record);
    }

    private function getFieldName($type)
    {
        return $type . '_id';
    }

    public function getComments(ShowCommentsRequest $request)
    {
        $model = new $this->commentTypes[$request->type];
        return ApiAnswerService::successfulAnswerWithData($model->getComments($request->id, $request->perpage));
    }

    public function getCommentsOnComment(ShowCommentsOnCommentRequest $request)
    {
        $model = new $this->commentTypes[$request->type];
        return ApiAnswerService::successfulAnswerWithData($model->getCommentsOnComment($request->id, $request->perpage));
    }
}
