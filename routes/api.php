<?php

use App\AuthApi\Http\Controllers\LoginController;
use App\AuthApi\Http\Controllers\RegisterController;
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
