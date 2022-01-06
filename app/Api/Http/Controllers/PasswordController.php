<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{


   /* public function showResetForm(Request $request, $token = null) {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request) {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    protected function rules() {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    protected function validationErrorMessages() {
        return [];
    }

    protected function credentials(Request $request) {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }*/

    public function resetPassword($user, $password) {

            $this->setUserPassword($user, $password);
//        $user->setRememberToken(Str::random(60));

//        event(new PasswordReset($user));
//        $this->guard()->login($user);

    }

    protected function setUserPassword($user, $password) {
        $user->password = Hash::make($password);
        $user->save();
    }

   /* protected function sendResetResponse(Request $request, $response) {
        if ($request->wantsJson()) {
            return new JsonResponse(['message' => trans($response)], 200);
        }

        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response) {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    public function broker() {
        return Password::broker();
    }

    protected function guard() {
        return Auth::guard();
    }*/

}
