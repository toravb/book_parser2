<?php

namespace App\Api\Http\Controllers;

use App\Api\Events\NewNotificationEvent;
use Illuminate\Support\Facades\Auth;
use App\Api\Interfaces\Types;
use App\Http\Controllers\Controller;
use App\Api\Http\Requests\CreateLikeRequest;
use App\Api\Http\Requests\DeleteLikeRequest;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{
    private array $likeTypes = [];

    public function __construct(Types $types)
    {
        $this->likeTypes = $types->getLikeTypes();
    }

    /**
     * @OA\Post(
     *        path="/likes",
     *        summary="Create the like",
     *        description="Set data to create a new like",
     *        tags={"Likes"},
     *        security={{"bearer_token":{}}},
     *        @OA\RequestBody(
     *            required=true,
     *            description="Pass data for creation",
     *            @OA\JsonContent(
     *                @OA\Property(property="type", type="string", description="Type of the liked entity",
     *                                              example="post"),
     *                @OA\Property(property="id", type="integer", description="Id of the liked entity",
     *                                            example="1")
     *           ),
     *        ),
     *        @OA\Response(
     *            response=200,
     *            description="Success",
     *            @OA\JsonContent(
     *                @OA\Property(property="status", type="string", example="success"),
     *                @OA\Property(
     *                    property="data",
     *                    type="integer", description="Count of likes for this entity",
     *                )
     *            )
     *        ),
     *        @OA\Response(
     *            response=422,
     *            description="Returns when data is not valid",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="The given data was invalid."),
     *                @OA\Property(
     *                    property="errors",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                        @OA\Property(property="text", type="string", example="Text is required"),
     *                    )
     *                )
     *            )
     *        ),
     *        @OA\Response(
     *            response=401,
     *            description="Returns when user is not authenticated",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Unauthenticated"),
     *            )
     *        ),
     *      @OA\Response(
     *            response=500,
     *            description="Returns when like did saved",
     *            @OA\JsonContent(
     *                @OA\Property(property="status", type="string", example="error"),
     *                @OA\Property(property="message", type="string", example="Something went wrong, try again later"),
     *            )
     *        )
     *     )
     */
    public function create(CreateLikeRequest $request)
    {
        $userId = Auth::id();
        $field = $this->getFieldName($request->type);

        $record = $this->likeTypes[$request->type]
            ::updateOrInsert([
                'user_id' => $userId,
                $field => $request->id,
            ]);

        if ($record) {
            NewNotificationEvent::dispatch(NewNotificationEvent::LIKED_COMMENT, $request->type,  $request->id, $userId);

            $likesCount = $this->likeTypes[$request->type]::where($field, $request->id)->count();
            return response()->json([
                'status' => 'success',
                'data' => $likesCount,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong, try again later',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getFieldName($type)
    {
        return $type . '_id';
    }

    /** @OA\Delete(
     *        path="/likes/{id}",
     *        summary="Delete the like",
     *        description="Delete like",
     *        tags={"Likes"},
     *        @OA\Parameter(
     *            description="Id of entity, that like need to delete",
     *            in="path",
     *            name="id",
     *            required=true,
     *            example="1",
     *        ),
     *        @OA\Parameter(
     *            description="Type of the entity, that like need to delete.",
     *            in="query",
     *            name="type",
     *            required=true,
     *            example="video",
     *        ),
     *        @OA\Response(
     *            response=200,
     *            description="Success",
     *            @OA\JsonContent(
     *                @OA\Property(property="status", type="string", example="success"),
     *                @OA\Property(property="data", type="integer", description="Count of likes for this entity",
     *                                              example=1)
     *            )
     *        ),
     *        @OA\Response(
     *            response=401,
     *            description="Returns when user is not authenticated",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Unauthenticated"),
     *            )
     *        ),
     *       @OA\Response(
     *            response=404,
     *            description="Returns when users like not find",
     *            @OA\JsonContent(
     *                @OA\Property(property="status", type="string", example="error"),
     *                @OA\Property(property="message", type="string", example="Not found"),
     *            )
     *        ),
     *       @OA\Response(
     *            response=422,
     *            description="Returns when data is not valid",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="The given data was invalid."),
     *                @OA\Property(
     *                    property="errors",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                        @OA\Property(property="text", type="string", example="Text is required"),
     *                    )
     *                )
     *            )
     *        )
     *     )
     */
    public function delete(DeleteLikeRequest $request)
    {
        $field = $this->getFieldName($request->type);
        $like = $this->likeTypes[$request->type]::where($field, $request->id)
            ->where('user_id', Auth::id())
            ->delete();
        if (!$like) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found',
            ], Response::HTTP_NOT_FOUND);
        } else {
            $likesCount = $this->likeTypes[$request->type]::where($field, $request->id)->count();
            return response()->json([
                'status' => 'success',
                'data' => $likesCount,
            ]);
        }
    }
}
