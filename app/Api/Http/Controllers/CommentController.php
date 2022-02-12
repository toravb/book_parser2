<?php

namespace App\Api\Http\Controllers;

use App\Api\Events\NewNotificationEvent;
use App\Api\Http\Requests\SaveCommentRequest;
use App\Api\Interfaces\Types;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private array $commentTypes;

    public function __construct(Types $types)
    {
        $this->commentTypes = $types->getCommentTypes();
    }

    public function saveComment(SaveCommentRequest $request)
    {
        $field = $this->getFieldName($request->type);
        $userId = Auth::id();
//        $recordExists = $this->commentTypes[$request->type]::where([
//            ['user_id', '=', $userId],
//            [$field, '=', $request->id]
//        ])->exists();
        $record = $this->commentTypes[$request->type]
            ::create(
                [
                    'user_id' => $userId,
                    $field => $request->id,
                    'content' => $request->text,
                    'parent_comment_id' => $request->parent_comment_id
                ]);
        NewNotificationEvent::dispatch(NewNotificationEvent::ANSWER_ON_COMMENT_AND_ALSO_COMMENTED, $request->type,  $record->id, $userId);

        return ApiAnswerService::successfulAnswerWithData($record);
    }

    private function getFieldName($type)
    {
        return $type . '_id';
    }

}
