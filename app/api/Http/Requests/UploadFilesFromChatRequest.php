<?php

namespace App\Http\Requests;

use App\Services\UploadService;
use Illuminate\Foundation\Http\FormRequest;

class UploadFilesFromChatRequest extends FormRequest
{
    private $maxSizeVideo;
    private $maxSizeVideoInBytes;
    private $chunkSizeVideo;
    private $maxCountChunks;
    private $mimeTypes;

    public function __construct(UploadService $uploadService)
    {
        $this->mimeTypes = implode(',', $uploadService->getMimeTypesForUploadInChat());

        $this->maxSizeVideo = config('filesystems.max_video_size') * 1024;

        $this->maxSizeVideoInBytes = $this->maxSizeVideo * 1024 * 1024;

        $this->chunkSizeVideo = config('filesystems.chunk_size_video') * 1024 * 1024;

        $this->maxCountChunks = floor($this->maxSizeVideoInBytes / $this->chunkSizeVideo);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->hasFile('file')) {
            $size = $this->file('file')->getSize();

            if ($size < $this->chunkSizeVideo && $this->dzchunkindex === null) {
                return ['file' => 'mimetypes:' . $this->mimeTypes];
            }
        }
        return [
            'dzchunkindex' => ['required', 'integer', 'max:' . $this->maxCountChunks],
            'dztotalfilesize' => ['required', 'integer', 'max:' . $this->maxSizeVideoInBytes],
            'dzchunksize' => ['required', 'integer'],
            'dztotalchunkcount' => ['required', 'integer'],
            'dzchunkbyteoffset' => ['required', 'integer'],
            'file' => ['required', 'file', 'max:' . $this->chunkSizeVideo]
        ];
    }
}
