<?php

use App\api\Http\Controllers\Auth\ForgotPasswordController;
use App\api\Http\Controllers\Auth\LoginController;
use App\api\Http\Controllers\Auth\RegistrationController;
use App\Api\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Password;

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
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
});

Route::post('/register', [RegistrationController::class, 'register']);

Route::post('/login', [LoginController::class, 'login']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendPasswordResetLink']);


//Route::get('/forgot-password', function () {
//    return view('auth.forgot-password');
//})->middleware('guest')->name('password.request');

Route::get('send-mail', function () {

    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];

    Mail::to('your_receiver_email@gmail.com')->send(new \App\Mail\MyTestMail($details));

    dd("Email is Sent.");
});

Route::get('/feedback', 'FeedbackController@index')->name('feedback.index');
Route::post('/feedback', 'FeedbackController@send')->name('feedback.send');

/*Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');*/

/*Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');
*/

