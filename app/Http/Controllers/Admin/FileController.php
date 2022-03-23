<?php

namespace App\Http\Controllers\Admin;

use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileDestroyRequest;

class FileController extends Controller
{
    public function destroy(FileDestroyRequest $request)
    {
        $filePath = str_replace(
            [
                config('app.url') . '/storage/',
                config('app.url') . '/',
            ],
            '',
            $request->file_path
        );

        if (!\Storage::exists($filePath)) {
            abort(404);
        }

        \Storage::delete($filePath);

        return ApiAnswerService::successfulAnswer();
    }
}
