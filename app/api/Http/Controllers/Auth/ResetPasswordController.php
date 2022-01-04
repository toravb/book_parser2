<?php

namespace App\api\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

    use ResetsPasswords;

    /**
     * @OA\Post(
     *     path="/password/reset",
     *     summary="Password reset",
     *     tags={"Auth"},
     *    @OA\RequestBody(
     *        required=true,
     *        description="Pass user credentials",
     *        @OA\JsonContent(
     *            required={"email","password","token","password"},
     * @OA\Property(property="token", type="string",
     *                                example="3905b4a959b11c50fe675f2090a227db03328857632307b9b342960630e9de32"),
     *            @OA\Property(property="email", type="string", example="test@gmail.com"),
     *            @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     * @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
     *        ),
     *     ),
     *     @OA\Response(response="200", description="Password reset successfully.",
     *                                  @OA\JsonContent(@OA\Property(property="message", type="string", example=""),)),
     *    @OA\Response(
     *        response=422,
     *        description="Returns when data is not valid",
     *        @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="The given data was invalid."),
     *           @OA\Property(
     *              property="errors",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                  @OA\Property(property="twitter", type="string", example="The twitter may not be greater than 1
     *                                                   characters."),
     *         ))
     *        )
     *    ),
     * )
     */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        $loginData = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!auth()->attempt($loginData)) {
            return response()->json(
                [
                    'message' => 'The give data was invalid.',
                    'errors' => [
                        'email' =>
                            [
                                'Sorry the email or password you entered is incorrect.
                        Please enter the correct one or use the password recovery.'
                            ]
                    ],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;

            return response()->json([
                'token' => $accessToken
            ]);
        }
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Failed, Invalid Token.']);
    }
}
