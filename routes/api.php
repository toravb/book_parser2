<?php

use App\Api\Http\Controllers\AuthorController;
use App\Api\Http\Controllers\AuthorPageController;
use App\Api\Http\Controllers\AuthorSeriesController;
use App\Api\Http\Controllers\CompilationController;
use App\Api\Http\Controllers\ProfileController;
use App\Api\Http\Controllers\CompilationLoadingController;
use App\Api\Http\Controllers\QuoteController;
use App\Api\Http\Controllers\RateController;
use App\Api\Http\Controllers\UserAuthorsController;
use App\api\Http\Controllers\UsersBooksController;
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
use App\Http\Controllers\ReadingSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Api\Http\Controllers\NotificationSettingsController;

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

Route::middleware('auth:api')->group(function () {
    Route::post('/profile', [ProfileUpdateController::class, 'update']);
    Route::post('/password_reset', [PasswordController::class, 'resetPassword']);
    Route::put('/notification_settings', [NotificationSettingsController::class, 'create']);
    Route::delete('/users', [UserController::class, 'destroy']);
    Route::get('/users', [ProfileController::class, 'profile']);
    Route::post('/users/authors', [UserAuthorsController::class, 'store']);
    Route::delete('/users/authors', [UserAuthorsController::class, 'destroy']);
    /**
     * Likes
     */
    Route::post('/likes', [LikeController::class, 'create']);
    Route::delete('/likes', [LikeController::class, 'delete']);

    /**
     * add book to list
     */
    Route::delete('/users/books', [BookController::class, 'deleteBookFromUsersList']);
    Route::put('/users/books', [BookController::class, 'changeBookStatus']);

    Route::group(['prefix' => 'quotes'], function () {
        Route::get('/', [QuoteController::class, 'index']);
        Route::get('/{id}', [QuoteController::class, 'show']);

        Route::post('/', [QuoteController::class, 'store']);
        Route::delete('/', [QuoteController::class, 'destroy']);
    });

    Route::group(['prefix' => 'reading_settings'], function () {
        Route::get('/', [ReadingSettingsController::class, 'index']);

        Route::put('/', [ReadingSettingsController::class, 'store']);
    });


    Route::post('/ratings', [RateController::class, 'store']);

    Route::post('/compilations', [CompilationController::class, 'store']);
    Route::post('/compilations/books', [BookController::class, 'saveBookToCompilation']);
    Route::delete('/compilations/books/delete', [BookController::class, 'deleteBookFromCompilation']);
    Route::get('/users/books', [UsersBooksController::class, 'showBooks']);
});


Route::post('/register', [RegisterController::class, 'registry']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/verify_email', [VerifyEmailController::class, 'verify'])->name('auth.verify_email');
//Social networks
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::post('/auth', [SocialAuthController::class, 'authConfirm']);
Route::post('/password_forgot', [ForgotPasswordController::class, 'forgot']);


Route::get('/genres', [CategoryController::class, 'show']);
Route::get('/selections', [CategoryController::class, 'showSelectionType']);

Route::get('/books', [BookController::class, 'show']);
Route::get('/books/{id}', [BookController::class, 'showSingle']);
Route::get('/books/read/{id}', [BookController::class, 'readBook']);
Route::get('/books/{id}/chapters', [BookController::class, 'showBookContents']);

Route::get('/compilations', [CompilationController::class, 'show']);
Route::get('public/compilations/{id}', [CompilationController::class, 'showCompilationDetails']);
Route::get('public/load/compilations/{id}', [CompilationLoadingController::class, 'compilationLoading']);

Route::post('/change-password',[PasswordController::class, 'resetPassword']);

Route::get('/author/page', [AuthorPageController::class, 'show']);
Route::get('/author/series/{id}', [AuthorSeriesController::class, 'showSeries']);
Route::get('/byletter/books',[BookController::class, 'showByLetterBook']);
Route::get('/byletter/authors',[AuthorController::class, 'showByLetterAuthor']);
