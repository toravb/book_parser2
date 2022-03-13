<?php

use App\Api\Http\Controllers\AudioBookController;
use App\Api\Http\Controllers\AuthorController;
use App\Api\Http\Controllers\AuthorPageController;
use App\Api\Http\Controllers\AuthorSeriesController;
use App\Api\Http\Controllers\BookController;
use App\Api\Http\Controllers\BookmarksController;
use App\Api\Http\Controllers\CategoryController;
use App\Api\Http\Controllers\ChaptersController;
use App\Api\Http\Controllers\ClaimFormsController;
use App\Api\Http\Controllers\CommentController;
use App\Api\Http\Controllers\CompilationController;
use App\Api\Http\Controllers\CompilationLoadingController;
use App\Api\Http\Controllers\FeedbackFormsController;
use App\Api\Http\Controllers\LikeController;
use App\Api\Http\Controllers\MainPageController;
use App\Api\Http\Controllers\PasswordController;
use App\Api\Http\Controllers\ProfileController;
use App\Api\Http\Controllers\QuoteController;
use App\Api\Http\Controllers\RateController;
use App\Api\Http\Controllers\ReviewController;
use App\Api\Http\Controllers\SocialNetworksController;
use App\Api\Http\Controllers\UserAuthorsController;
use App\Api\Http\Controllers\UserController;
use App\Api\Http\Controllers\UsersRecommendationsController;
use App\AuthApi\Http\Controllers\LoginController;
use App\AuthApi\Http\Controllers\RegisterController;
use App\AuthApi\Http\Controllers\SocialAuthController;
use App\AuthApi\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ReadingSettingsController;
use App\Api\Http\Controllers\NotificationController;
use App\Api\Http\Controllers\SearchController;
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
/**
 * Main Page
 */
Route::get('/home', [MainPageController::class, 'home']);

Route::get('/novelties', [BookController::class, 'novelties']);

/**
 * Profile social networks
 */
Route::get('/profile/auth/{provider}', [SocialNetworksController::class, 'redirectToGoogle']);
Route::get('/profile/auth/{provider}/callback', [SocialNetworksController::class, 'handleGoogleCallback']);

Route::middleware('auth:api')->group(function () {

    /*
     * User and profile
     */
    Route::group(['prefix' => 'profile'], function () {

        //Social networks
        Route::get('/temp_token', [SocialNetworksController::class, 'getTempToken']);


        Route::get('/', [ProfileController::class, 'profile']);

        Route::post('/', [ProfileController::class, 'update']);
        Route::post('/password-change', [PasswordController::class, 'change']);

        Route::delete('/', [ProfileController::class, 'destroy']);

        Route::group(['prefix' => 'lists'], function () {
            Route::group(['prefix' => 'books'], function () {
                Route::get('/', [BookController::class, 'showUserBooks']);
                Route::put('/', [BookController::class, 'changeBookStatus']);
                Route::delete('/', [BookController::class, 'deleteBookFromUsersList']);
            });

            Route::group(['prefix' => 'audio-books'], function () {
                Route::put('/', [AudioBookController::class, 'changeCreateStatus']);
                Route::delete('/', [AudioBookController::class, 'deleteAudioBookFromUsersList']);
            });

            Route::group(['prefix' => 'authors'], function () {
                Route::get('/', [UserAuthorsController::class, 'list']);
                Route::post('/', [UserAuthorsController::class, 'store']);
                Route::delete('/', [UserAuthorsController::class, 'destroy']);
            });
        });
    });
    /*
     * -----------
     */

    Route::get('/users/user_id', [UserController::class, 'getUserId']);


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
        Route::get('/list', [ReviewController::class, 'showUserReviews']);
        Route::put('/', [ReviewController::class, 'saveUpdateReview']);
        Route::delete('/', [ReviewController::class, 'delete']);
    });

    /**
     * Comments
     */
    Route::group(['prefix' => 'comments'], function () {
        Route::post('/', [CommentController::class, 'saveComment']);

    });

    /*
     * Quotes
     */
    Route::group(['prefix' => 'quotes'], function () {
        Route::get('/', [QuoteController::class, 'index']);
        Route::get('/list', [QuoteController::class, 'showUserQuotes']);
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
    /**
     * Notifications
     */
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'get']);
    });
});

/**
 * genres
 */
Route::group(['prefix' => 'genres'], function () {
    Route::get('/', [CategoryController::class, 'show']);
    Route::get('/books', [CategoryController::class, 'withBooksCount']);
    Route::get('/audio-books', [CategoryController::class, 'withAudioBooksCount']);
});

/**
 * Get comments by type
 */
Route::group(['prefix' => 'comments'], function () {
    Route::get('/{type}/{id}', [CommentController::class, 'getComments']);
    Route::get('/{id}', [CommentController::class, 'getCommentsOnComment']);
});

/**
 * Get reviews by model type
 */
Route::get('/{type}/{id}/reviews', [ReviewController::class, 'getReviews']);


Route::get('/selections', [CategoryController::class, 'showSelectionType']);

/**
 * Users recommendations
 */
Route::post('/users-recommend', [UsersRecommendationsController::class, 'saveUserRecommend']);

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
    Route::get('/filter', [BookController::class, 'filteringByLetterPage']);
    Route::get('/letter/{letter}', [BookController::class, 'showByLetter']);

    Route::get('/{id}', [BookController::class, 'showSingle']);
    Route::get('/{book}/chapters', [ChaptersController::class, 'showBookChapters']);
    Route::get('/{id}/read', [BookController::class, 'readBook']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/{book}/bookmarks', [BookController::class, 'getBookmarks']);

        // Ratings
        Route::group(['prefix' => 'ratings'], function () {
            Route::post('/', [RateController::class, 'store']);
        });

    });

});
/*
 * --------
 */

/*
 * Compilations
 */
Route::group(['prefix' => 'compilations'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', [CompilationController::class, 'store']);
        Route::get('/user', [CompilationController::class, 'showUserCompilations']);
        Route::post('/books', [BookController::class, 'saveBookToCompilation']);
        Route::delete('/books/delete', [BookController::class, 'deleteBookFromCompilation']);
    });

    Route::get('/', [CompilationController::class, 'show']);
    Route::get('/{id}', [CompilationController::class, 'showCompilationDetails']);
    Route::get('/{id}/load', [CompilationLoadingController::class, 'compilationLoading']);


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
    Route::get('/filter', [AuthorController::class, 'filterByLetter']);
    Route::get('/letter/{letter}', [AuthorController::class, 'showByLetter']);
    Route::get('/{author}/quotes', [AuthorPageController::class, 'showQuotes']);
    Route::get('/{author}/reviews', [AuthorPageController::class, 'showReviews']);
    Route::get('/{id}/books', [AuthorController::class, 'showOtherBooks']);
    Route::get('/{id}/audio-books', [AuthorController::class, 'showOtherAudioBooks']);
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
Route::post('/support', [FeedbackFormsController::class, 'store']);
Route::post('/claim', [ClaimFormsController::class, 'store']);

/**
 * Search
 */
Route::get('/search', [SearchController::class, 'search']);
