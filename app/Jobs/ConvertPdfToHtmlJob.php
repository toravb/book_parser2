<?php

namespace App\Jobs;

use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ConvertPdfToHtmlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 360;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public int $bookId, public string $path)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fullPath = storage_path('app/public/' . $this->path) ;

        $pdf = new \TonchikTm\PdfToHtml\Pdf($fullPath, [

            'pdftohtml_path' => base_path(config('filesystems.pdftohtml_path')),
            'pdfinfo_path' => base_path(config('filesystems.pdfinfo_path')),
            'html' => [ // settings for processing html
                'inlineCss' => true, // replaces css classes to inline css rules
                'inlineImages' => true, // looks for images in html and replaces the src attribute to base64 hash
                'onlyContent' => true, // takes from html body content only
            ]
        ]);
        $pdfInfo = $pdf->getInfo();

        // get count pages
        $countPages = $pdf->countPages();

        for ($i = 1; $i < $countPages; $i++){

            $content = $pdf->getHtml()->getPage($i);
            dump($i);
            Page::create([
                'link' => '',
                'content' => $content ,
                'page_number' => $i,
                'book_id' => $this->bookId
            ]);
        }
      
        Storage::disk('public')->delete($this->path);
    }
}
