<?php

use App\Api\Http\Controllers\BookController;
use App\AuthApi\Http\Controllers\LoginController;
use App\AuthApi\Http\Controllers\RegisterController;
use App\Api\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'registry']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/genres', [CategoryController::class, 'show'])->name('category');
Route::get('/books', [BookController::class, 'show'])->name('showList');
Route::get('/books/{id}', [BookController::class, 'showSingle'])->name('showSingle');
Route::put('/savebook', [BookController::class, 'saveBook'])->name('saveBook');

