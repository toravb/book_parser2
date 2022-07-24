<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Filters\BookFilter;
use App\Admin\Services\ConversionFileService;
use App\Api\Services\ApiAnswerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\ParsePdfRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class BooksController extends Controller
{
    public function index(Book $books, BookFilter $filter)
    {
        $books = $books->dataForAdminPanel()->filter($filter)->paginate(25)->withQueryString();

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function edit($book, Book $books)
    {
        $book = $books->dataForAdminPanel()
            ->addSelect([
                'meta_description',
                'meta_keywords',
                'alias_url',
                'text',
            ])
            ->findOrFail($book);

        return view('admin.books.edit', compact('book'));
    }

    public function store(StoreBookRequest $request, Book $bookModel)
    {
        try {
            DB::beginTransaction();
            $book = $bookModel->saveFromRequest($request);
            $path = $request->file('file_for_parsing')->store('admin', 'public');
            $conversionFileService = new ConversionFileService();
            $conversionFileService->convertToHtml($book->id, $path);
            DB::commit();
            return redirect()->route('admin.books.edit', $book)->with('success', 'Книга успешно создана!Обработка займет некоторое время');
        } catch (Exception $exception) {
            DB::rollBack();
            $validator = Validator::make([], []);
            $validator->errors()->add('file_for_parsing', $exception->getMessage());
            return redirect(route('admin.books.create'))
                ->withErrors($validator)
                ->withInput();
        }

    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->saveFromRequest($request);

        return redirect()->route('admin.books.edit', $book)->with('success', 'Книга успешно обновлена!');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return ApiAnswerService::redirect(route('admin.books.index'));
    }

    public function uploadPage()
    {
        $genres = Genre::all();
        return view('admin.books.create', ['genres' => $genres]);
    }

    public function parsePdf(Request $request, ConversionFileService $conversionFileService)
    {
        $output_folder=storage_path('app/public/admin/myfolder');;
        if (!file_exists($output_folder)) { mkdir($output_folder, 0777, true);}
        $firstpage = 1;
        $lastpage = 3;
        $source_pdf =  storage_path('app/public/admin/3.pdf');
        $a= passthru("pdftohtml -f $firstpage -l $lastpage $source_pdf $output_folder/new_html_file_name",$b);
        var_dump($a);
//        $conversionFileService->convertToHtml($request->id, $request->path);
//        unlink($request->pathForConversion);
        return ApiAnswerService::successfulAnswer();
    }
}
