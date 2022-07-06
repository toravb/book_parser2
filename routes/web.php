<?php

use App\Api\Http\Controllers\StaticPagesController;
use App\Http\Controllers\Admin\AudioBooksController;
use App\Http\Controllers\Admin\AuthorsController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\BooksController;
use App\Http\Controllers\Admin\CompilationController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\GenresController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ReviewTypesController;
use App\Http\Controllers\Admin\YearsController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin-panel', 'middleware' => 'auth'], function () {
    Route::delete('/file-remove', [FileController::class, 'destroy'])->name('file-remove');

    /*
     * New admin panel
     */
    Route::get('/', [HomeController::class, 'index'])->name('index');

    /*
     * Authors
     */
    Route::resource('authors', AuthorsController::class)->except(['show']);

    /*
     * Books
     */
    Route::resource('books', BooksController::class)->except(['show']);

    /*
     * Page
     */
    Route::resource('books.pages', PagesController::class)->except(['show'])->scoped();
    Route::post('/pages-image-store', [PagesController::class, 'storeImage'])->name('pages.image-store');

    /*
     * Audio books
     */
    Route::resource('audio-books', AudioBooksController::class)->except(['show']);

    /*
     * Genres
     */
    Route::resource('genres', GenresController::class)->except(['show']);

    /*
     * Review types
     */
    Route::resource('review-types', ReviewTypesController::class)->except(['show']);

    /*
     * Years
     */
    Route::resource('years', YearsController::class)->except(['show', 'create']);

    /*
     * Banners
     */
    Route::resource('banners', BannersController::class)->except(['show']);


    /*
     * Compilations
     */
    Route::resource('compilations', CompilationController::class)->except(['show']);

    /*
     * Old admin panel

    Route::group(['as' => 'parser.', 'prefix' => 'parser'], function () {
        Route::get('/', [ParserController::class, 'index']);
        Route::get('/pages', [PageController::class, 'index'])->name('pages');
        Route::get('/proxy/show', [PageController::class, 'showProxies'])->name('show.proxies');
        Route::get('/proxy/settings', [ProxySettingsController::class, 'index'])->name('proxy.settings');

        Route::group(['prefix' => 'parse'], function () {
            Route::post('/add/pages', [ParserController::class, 'addPagesToQueue'])->name('add.pages');

            Route::group(['as' => 'parse.'], function () {
                Route::get('/page', [ParserController::class, 'parsePages'])->name('page');
                Route::get('/proxy', [ParserController::class, 'parseProxy'])->name('proxy');

                Route::post('/page', [ParserController::class, 'parsePages'])->name('page.post');
                Route::post('/page_image', [ParserController::class, 'parsePageImage'])->name('pageImage');
                Route::post('/sitemap', [ParserController::class, 'parseSiteMap'])->name('sitemap');
            });
        });

        Route::group(['as' => 'parse.'], function () {
            Route::get('/excel/download', [ParserController::class, 'getDownload'])->name('download');
            Route::get('/excel/generate', [ParserController::class, 'generateExcel'])->name('generate');

            Route::post('/links', [ParserController::class, 'parseLink'])->name('links');
            Route::post('/books', [ParserController::class, 'parseBooks'])->name('books');
            Route::post('/pages', [ParserController::class, 'parsePages'])->name('pages');
            Route::post('/images', [ParserController::class, 'parseImages'])->name('images');
        });


    });

    Route::group(['as' => 'profile.', 'prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('change');

        Route::post('/', [ProfileController::class, 'change'])->name('change.data');
    });

    Route::post('/add/auth', [ProxySettingsController::class, 'addAuthData'])->name('add.authdata');

    Route::group(['as' => 'audio.', 'prefix' => 'audio'], function () {
        Route::get('/menu', [AdminController::class, 'index'])->name('menu');
        Route::prefix('{site}')->group(function () {
            Route::group(['as' => 'parsing.', 'prefix' => 'parsing'], function () {
                Route::post('/default', [AdminController::class, 'startDefaultParsing'])->name('default');
                Route::post('/authors', [AdminController::class, 'startAuthorsParsing'])->name('authors');
                Route::post('/books', [AdminController::class, 'startBooksParsing'])->name('books');
                Route::post('/images', [AdminController::class, 'startImagesParsing'])->name('images');
                Route::post('/audiobook', [AdminController::class, 'startAudioBooksParsing'])->name('audio');
                Route::post('/check', [AdminController::class, 'checkErrors'])->name('check');
            });
        });

        Route::group(['as' => 'books.', 'prefix' => 'books'], function () {
            Route::get('/list', [AdminController::class, 'booksList'])->name('list');
            Route::get('/table', [AdminController::class, 'booksTable'])->name('table');
            Route::get('/authors', [AdminController::class, 'authorsList'])->name('authors');
            Route::get('/actors', [AdminController::class, 'actorsList'])->name('actors');
            Route::get('/genres/{genre}', [AdminController::class, 'booksGenre'])->name('genre');
            Route::get('/series/{series}', [AdminController::class, 'booksSeries'])->name('series');
            Route::get('/authors/{author}', [AdminController::class, 'booksFromAuthor'])->name('author');
            Route::get('/actors/{actor}', [AdminController::class, 'booksFromActor'])->name('actor');
            Route::get('/{book}', [AdminController::class, 'booksItem'])->name('show');
        });
    });

    Route::group(['as' => 'books.', 'prefix' => 'books'], function () {
        Route::get('/', [PageController::class, 'books'])->name('show');
        Route::get('/{id}', [PageController::class, 'booksPages'])->name('item');
    });
    */
});
Route::get('/api/documentation', [StaticPagesController::class, 'documentation']);
Route::view('/wss', 'wss');


require __DIR__ . '/auth.php';
