<?php

use App\Api\Http\Controllers\AudioBookController;
use App\Api\Http\Controllers\AuthorController;
use App\Api\Http\Controllers\AuthorPageController;
use App\Api\Http\Controllers\AuthorSeriesController;
use App\Api\Http\Controllers\BookController;
use App\Api\Http\Controllers\BookmarksController;
use App\Api\Http\Controllers\CategoryController;
use App\Api\Http\Controllers\ChaptersController;
use App\Api\Http\Controllers\CommentController;
use App\Api\Http\Controllers\CompilationController;
use App\Api\Http\Controllers\CompilationLoadingController;
use App\Api\Http\Controllers\LikeController;
use App\Api\Http\Controllers\PasswordController;
use App\Api\Http\Controllers\ProfileController;
use App\Api\Http\Controllers\QuoteController;
use App\Api\Http\Controllers\RateController;
use App\Api\Http\Controllers\ReviewController;
use App\Api\Http\Controllers\UserAuthorsController;
use App\AuthApi\Http\Controllers\LoginController;
use App\AuthApi\Http\Controllers\RegisterController;
use App\AuthApi\Http\Controllers\SocialAuthController;
use App\AuthApi\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ReadingSettingsController;
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

/*
 * Auth
 */
Route::group(['middleware' => 'guest'], function () {
    Route::post('/register', [RegisterController::class, 'registry']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/verify_email', [VerifyEmailController::class, 'verify'])->name('auth.verify_email');

    //Social networks
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToGoogle']);
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    Route::post('/auth', [SocialAuthController::class, 'authConfirm']);
    Route::post('/password_forgot', [PasswordController::class, 'forgot']);
});
/*
 * -------
 */


Route::middleware('auth:api')->group(function () {
    /*
     * User and profile
     */
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'profile']);

        Route::post('/', [ProfileController::class, 'update']);
        Route::post('/password-change', [PasswordController::class, 'change']);

        Route::delete('/', [ProfileController::class, 'destroy']);

        Route::group(['prefix' => 'lists'], function () {
            Route::group(['prefix' => 'books'], function () {
                Route::put('/', [BookController::class, 'changeBookStatus']);
                Route::delete('/', [BookController::class, 'deleteBookFromUsersList']);
            });

            Route::group(['prefix' => 'audio-books'], function () {
                Route::put('/', [AudioBookController::class, 'changeCreateStatus']);
                Route::delete('/', [AudioBookController::class, 'deleteAudioBookFromUsersList']);
            });

            Route::group(['prefix' => 'authors'], function () {
                Route::post('/', [UserAuthorsController::class, 'store']);
                Route::delete('/', [UserAuthorsController::class, 'destroy']);
            });
        });
    });
    /*
     * -----------
     */


    /**
     * Likes
     */
    Route::group(['prefix' => 'likes'], function () {
        Route::post('/', [LikeController::class, 'create']);
        Route::delete('/', [LikeController::class, 'delete']);
    });

    /**
     * Reviews
     */
    Route::group(['prefix' => 'reviews'], function () {
        Route::put('/', [ReviewController::class, 'saveUpdateReview']);
        Route::delete('/', [ReviewController::class, 'delete']);
    });

    /**
     * Comments
     */
    Route::group(['prefix' => 'comments'], function (){
        Route::put('/',[CommentController::class, 'saveChangeComment']);
    });

    /*
     * Quotes
     */
    Route::group(['prefix' => 'quotes'], function () {
        Route::get('/', [QuoteController::class, 'index']);
        Route::get('/{id}', [QuoteController::class, 'show']);

        Route::post('/', [QuoteController::class, 'store']);
        Route::delete('/', [QuoteController::class, 'destroy']);
    });

    /*
     * Reading settings
     */
    Route::group(['prefix' => 'reading_settings'], function () {
        Route::get('/', [ReadingSettingsController::class, 'index']);

        Route::put('/', [ReadingSettingsController::class, 'store']);
    });


    /*
     * Bookmarks
     */
    Route::group(['prefix' => 'bookmarks'], function () {
        Route::post('/', [BookmarksController::class, 'create']);
        Route::delete('/{bookmark}', [BookmarksController::class, 'destroy']);
    });
});

Route::get('/genres', [CategoryController::class, 'show']);
Route::get('/selections', [CategoryController::class, 'showSelectionType']);

/*
 * Show available reviews types
 */
Route::get('/review-types', [ReviewController::class, 'index']);
/*
 * -----------------------------
 */


/*
 * Books
 */
Route::group(['prefix' => 'books'], function () {
    Route::get('/', [BookController::class, 'show']);

    // Search by letter
    Route::get('/letter/{letter}', [BookController::class, 'showByLetter']);

    Route::get('/{id}', [BookController::class, 'showSingle']);
    Route::get('/{book}/chapters', [ChaptersController::class, 'showBookChapters']);
    Route::get('/{id}/read', [BookController::class, 'readBook']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/{book}/bookmarks', [BookController::class, 'getBookmarks']);
    });

    // Ratings
    Route::group(['prefix' => 'ratings'], function () {
        Route::post('/', [RateController::class, 'store']);
    });
});
/*
 * --------
 */

/*
 * Compilations
 */
Route::group(['prefix' => 'compilations'], function () {
    Route::get('/', [CompilationController::class, 'show']);
    Route::get('/{id}', [CompilationController::class, 'showCompilationDetails']);
    Route::get('/{id}/load', [CompilationLoadingController::class, 'compilationLoading']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', [CompilationController::class, 'store']);
        Route::post('/books', [BookController::class, 'saveBookToCompilation']);
        Route::delete('/books/delete', [BookController::class, 'deleteBookFromCompilation']);
    });
});
/*
 * --------
 */

/*
 * Authors
 */
Route::group(['prefix' => 'authors'], function () {
    Route::get('/page', [AuthorPageController::class, 'show']);
    Route::get('/series/{id}', [AuthorSeriesController::class, 'showSeries']);
    Route::get('/letter/{letter}', [AuthorController::class, 'showByLetter']);
});
/*
 * --------
 */

/*
 * AudioBooks
 */
Route::group(['prefix' => 'audio-books'], function () {
    Route::get('/genres', [CategoryController::class, 'showAudioBookGenres']);
    Route::get('/{id}', [AudioBookController::class, 'showAudioBookDetails']);
    Route::get('/{id}/listen', [AudioBookController::class, 'listeningMode']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/store-rating', [RateController::class, 'storeRateAudioBook']);
    });
});
/*
 * -------
 */
