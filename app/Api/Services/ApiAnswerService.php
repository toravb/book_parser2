<?php


namespace App\Api\Services;

use Symfony\Component\HttpFoundation\Response;

class ApiAnswerService
{
    public static function successfulAnswer($status = null): \Illuminate\Http\JsonResponse
    {
        if ($status === null) {
            $status = Response::HTTP_OK;
        }

        return response()->json(['status' => 'success'], $status);
    }

    public static function successfulAnswerWithData($data, $status = null): \Illuminate\Http\JsonResponse
    {
        if ($status === null) {
            $status = Response::HTTP_OK;
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], $status);
    }

    public static function redirect(string $route): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            'status' => 'success',
            'data' => [
                'action' => 'redirect',
                'route' => $route
            ]
        ], 200);
    }

    public static function errorAnswer($message, $status = null): \Illuminate\Http\JsonResponse
    {
        if ($status === null) {
            $status = Response::HTTP_OK;
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => $message
            ],
            $status
        );
    }
}
