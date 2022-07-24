<?php

namespace App\Admin\Services;

use App\Jobs\ConvertPdfToHtmlJob;
use App\Services\GenerateUniqueTokenService;
use Error;
use Exception;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;
use ZipArchive;
use Illuminate\Support\Facades\Http;

class ConversionFileService
{
    public function convertToHtml(int $bookId, string $path)
    {
        $info = new SplFileInfo($path);
        $extension = $info->getExtension();

        $fullPath = storage_path('app/public/' . $path);
        $pathAfterConversion = '';
        if ($extension === 'zip') {
            $unzip = new ZipArchive;
            $out = $unzip->open($fullPath);
            if ($out === TRUE) {
                if ($unzip->numFiles !== 1) {
                    Storage::disk('public')->delete($path);
                    throw new Exception('В архиве должен быть только один файл');
                }

                $pathToStore = storage_path('app/public/' . $info->getPath());
                $unzip->extractTo($pathToStore);
                $unzippedFilename = $info->getPath() . '/' . $unzip->getNameIndex(0);
                $unzip->close();

                $unzippedInfo = new SplFileInfo($unzippedFilename);
                $extension = $unzippedInfo->getExtension();
                if(!in_array($extension, ['fb2'])) {
                    Storage::disk('public')->delete($path);
                    Storage::disk('public')->delete($info->getPath() . '/' . $unzippedInfo->getFilename() );
                    throw new Exception('Неподдерживаемый файл для конвертирования в архиве');
                }

                $uniqueName =  GenerateUniqueTokenService::createTokenWithoutUserId();
                $path = $info->getPath() . '/' . $uniqueName . '.' . $extension;
                $storagePath = $info->getPath() . '/' . $uniqueName . '.pdf';
//                $pathAfterConversion = storage_path('app/public/' .  $storagePath);
                $pathAfterConversion = storage_path('app/public/html' );

                Storage::disk('public')->move($unzippedFilename, $path);
                $fullPath = storage_path('app/public/' . $path);

            } else {
                throw new Exception('Архив не открывается');
            }
        }
        if ($extension === 'epub') {
            $uniqueName =  GenerateUniqueTokenService::createTokenWithoutUserId();
            $storagePath = $info->getPath() . '/' . $uniqueName . '.pdf';
//            $pathAfterConversion = storage_path('app/public/' .  $storagePath);
            $pathAfterConversion = storage_path('app/public/html' );
        }


        match ($extension) {
            'pdf' => ConvertPdfToHtmlJob::dispatch($bookId, $path),
            'fb2' => $this->sendToNode($fullPath, $pathAfterConversion, $bookId, $storagePath),
            'epub' => $this->sendToNode($fullPath, $pathAfterConversion, $bookId, $storagePath),
        };
    }

    public function sendToNode(string $pathForConversion, string $pathAfterConversion, int $boolId, string $storagePath)
    {
        $response = Http::post(config('app.node_url') . '/convert', [
            'pathForConversion' => $pathForConversion,
            'pathAfterConversion' => $pathAfterConversion,
            'bookId' => $boolId,
            'storagePath' => $storagePath
        ]);
    }
}
