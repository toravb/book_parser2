<?php

use App\Api\Http\Controllers\CompilationController;
use App\Api\Http\Controllers\RateController;
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

Route::middleware('auth:api')->group(function (){
    Route::post('/profile', [ProfileUpdateController::class, 'update']);
    Route::post('/password_reset', [PasswordController::class, 'resetPassword']);
    Route::put('/notification_settings', [NotificationSettingsController::class, 'create']);
    Route::delete('/users', [UserController::class, 'destroy']);


    /**
     * Likes
     */
    Route::post('/likes', [LikeController::class, 'create']);
    Route::delete('/likes', [LikeController::class, 'delete']);
    /**
     * add book to list
    */
    Route::put('/books/save', [BookController::class, 'saveBook'])->name('saveBook');

    Route::post('/ratings', [RateController::class, 'store'])->name('storeRating');

    Route::post('/compilations', [CompilationController::class, 'store'])->name('storeCompilation');
    Route::get('/users/books', [UsersBooksController::class, 'showBooks']);
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



