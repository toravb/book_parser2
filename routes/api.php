<?php

use App\Api\Http\Controllers\CompilationController;
use App\Api\Http\Controllers\RateController;
use App\AuthApi\Http\Controllers\ForgotPasswordController;
use App\Api\Http\Controllers\BookController;
use App\Api\Http\Controllers\PasswordController;
use App\Api\Http\Controllers\ProfileUpdateController;
use App\Api\Http\Controllers\UserController;
use App\AuthApi\Http\Controllers\LoginController;
use App\AuthApi\Http\Controllers\RegisterController;
use App\Api\Http\Controllers\CategoryController;
use App\AuthApi\Http\Controllers\ResetPasswordController;
use App\AuthApi\Http\Controllers\SocialAuthController;
use App\AuthApi\Http\Controllers\VerifyEmailController;
use App\Api\Http\Controllers\LikeController;
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

Route::middleware('auth:api')->group(function (){
    Route::post('/profile', [ProfileUpdateController::class, 'update']);
    Route::post('/password_reset', [PasswordController::class, 'resetPassword']);
    /**
     * Likes
     */
    Route::post('/likes', [LikeController::class, 'create']);
    Route::delete('/likes', [LikeController::class, 'delete']);
    /**
     * add book to list
    */
    Route::put('/books/save', [BookController::class, 'saveBookToUsersList'])->name('saveBookToUsersList');
    Route::delete('/books/delete', [BookController::class, 'deleteBookFromUsersList'])->name('deleteBookFromUsersList');

    Route::post('/ratings', [RateController::class, 'store'])->name('storeRating');

    Route::post('/compilations', [CompilationController::class, 'store'])->name('storeCompilation');
    Route::post('/compilations/books/add', [BookController::class, 'saveBookToCompilation'])->name('saveBookToCompilation');
    Route::delete('/compilations/books/delete', [BookController::class, 'deleteBookFromCompilation'])->name('deleteBookFromCompilation');

});


Route::post('/register', [RegisterController::class, 'registry']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/verify_email', [VerifyEmailController::class, 'verify'])->name('auth.verify_email');
//Social networks
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/{provider}/callback',  [SocialAuthController::class, 'handleGoogleCallback']);
Route::post('/auth', [SocialAuthController::class, 'authConfirm']);
Route::post('/password_forgot', [ForgotPasswordController::class, 'forgot']);


Route::get('/genres', [CategoryController::class, 'show'])->name('category');
Route::get('/selections', [CategoryController::class, 'showSelectionType'])->name('selectionType');

Route::get('/books', [BookController::class, 'show'])->name('showList');
Route::get('/books/{id}', [BookController::class, 'showSingle'])->name('showSingle');

Route::get('/compilations', [CompilationController::class, 'show'])->name('compilationList');

Route::post('/change-password',[PasswordController::class, 'resetPassword']);
Route::post('/delete-account', [UserController::class, 'destroy']);
//Route::post('/notification-settings', [])

