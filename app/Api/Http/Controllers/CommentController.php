<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\SaveUpdateCommentRequest;
use App\Api\Interfaces\Types;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    private array $commentTypes;

    public function __construct(Types $types)
    {
        $this->commentTypes = $types->getCommentTypes();
    }

    public function saveChangeComment(SaveUpdateCommentRequest $request)
    {
        $field = $this->getFieldName($request->type);
        $record = $this->commentTypes[$request->type]
            ::updateOrCreate(
                [
                    'user_id' => \auth()->id(),
                    $field => $request->id
                ],
                [
                    'content' => $request->text,
                ]);
        return ApiAnswerService::successfulAnswerWithData($record);
    }

    private function getFieldName($type)
    {
        return $type . '_id';
    }

}
