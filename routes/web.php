<?php

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


Route::get('/dashboard', 'App\Http\Controllers\Parser\Admin\DashboardController@main')->middleware(['auth'])->name('dashboard');

Route::post('/parser/links', 'App\Http\Controllers\Parser\Admin\ParserController@parseLink')->middleware(['auth'])->name('parser.parse.links');
Route::post('/parser/books', 'App\Http\Controllers\Parser\Admin\ParserController@parseBooks')->middleware(['auth'])->name('parser.parse.books');
Route::post('/parser/pages', 'App\Http\Controllers\Parser\Admin\ParserController@parsePages')->middleware(['auth'])->name('parser.parse.pages');
Route::post('/parser/images', 'App\Http\Controllers\Parser\Admin\ParserController@parseImages')->middleware(['auth'])->name('parser.parse.images');
Route::get('/books', 'App\Http\Controllers\Parser\Admin\PageController@books')->middleware(['auth'])->name('books.show');
Route::get('/books/{id}', 'App\Http\Controllers\Parser\Admin\PageController@booksPages')->middleware(['auth'])->name('books.item');

Route::post('/add/auth', 'App\Http\Controllers\Parser\Admin\ProxySettingsController@addAuthData')->middleware(['auth'])->name('add.authdata');
Route::get('/parser/pages', 'App\Http\Controllers\Parser\Admin\PageController@index')->middleware(['auth'])->name( 'parser.pages');
Route::get('/parser/proxy/show', 'App\Http\Controllers\Parser\Admin\PageController@showProxies')->middleware(['auth'])->name( 'show.proxies');
Route::get('/parser/proxy/settings', 'App\Http\Controllers\Parser\Admin\ProxySettingsController@index')->middleware(['auth'])->name( 'proxy.settings');
Route::get('/parser', 'App\Http\Controllers\Parser\Admin\ParserController@index')->middleware(['auth'])->name( 'parser');
Route::get('/profile', 'App\Http\Controllers\Parser\Admin\ProfileController@index')->middleware(['auth'])->name( 'profile.change');
Route::post('/profile', 'App\Http\Controllers\Parser\Admin\ProfileController@change')->middleware(['auth'])->name( 'profile.change.data');
Route::post('/parser/parse/page','App\Http\Controllers\Parser\Admin\ParserController@parsePage')->middleware(['auth'])->name('parser.parse.page.post');
Route::post('/parser/parse/add/pages','App\Http\Controllers\Parser\Admin\ParserController@addPagesToQueue')->middleware(['auth'])->name('parser.add.pages');
Route::get('/parser/parse/page','App\Http\Controllers\Parser\Admin\ParserController@parsePage')->middleware(['auth'])->name('parser.parse.page');
Route::post('/parser/parse/page_image','App\Http\Controllers\Parser\Admin\ParserController@parsePageImage')->middleware(['auth'])->name('parser.parse.pageImage');
Route::get('/parser/parse/proxy','App\Http\Controllers\Parser\Admin\ParserController@parseProxy')->middleware(['auth'])->name('parser.parse.proxy');
Route::post('/parser/parse/sitemap','App\Http\Controllers\Parser\Admin\ParserController@parseSiteMap')->middleware(['auth'])->name('parser.parse.sitemap');
Route::get('/parser/excel/download','App\Http\Controllers\Parser\Admin\ParserController@getDownload')->middleware(['auth'])->name('parser.parse.download');
Route::get('/parser/excel/generate','App\Http\Controllers\Parser\Admin\ParserController@generateExcel')->middleware(['auth'])->name('parser.parse.generate');


Route::prefix('audio')->name('audio.')->middleware('auth')->group(function (){
    Route::get('/menu', [\App\Http\Controllers\Audio\AdminController::class, 'index'])->name('menu');
    Route::prefix('{site}/parsing')->name('parsing.')->group(function (){
        Route::post('default', [\App\Http\Controllers\Audio\AdminController::class, 'startDefaultParsing'])->name('default');
        Route::post('authors', [\App\Http\Controllers\Audio\AdminController::class, 'startAuthorsParsing'])->name('authors');
        Route::post('books', [\App\Http\Controllers\Audio\AdminController::class, 'startBooksParsing'])->name('books');
    });

});


require __DIR__.'/auth.php';
