<?php

namespace App\Services;

class UploadService
{
    protected $uploadFilesFromChat = [
        'video/mp4' => 'video',
        'image/jpg' => 'photo',
        'image/jpeg' => 'photo',
        'image/png' => 'photo'
    ];

    public function getMimeTypesForUploadInChat()
    {
        return array_keys($this->uploadFilesFromChat);
    }

    public function getTypeOfFile($mimeType)
    {
        return $this->uploadFilesFromChat[$mimeType];
    }
}
