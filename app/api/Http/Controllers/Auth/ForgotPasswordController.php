<?php

namespace App\api\Http\Controllers\Auth;

use App\api\Http\Controllers\Controller;
use App\api\Http\Requests\ForgotPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

    use SendsPasswordResetEmails;


    /**
     * @OA\Post(
     * path="/password/forgot",
     * summary="Forgot password",
     * description="Send user Email for recovering password",
     * tags={"Auth"},
     *       @OA\RequestBody(
     *         required=true,
     *         description="Pass user's email",
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", example="example@gmail.com")
     *         ),
     *      ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="integer", example="success"),
     *          @OA\Property(property="data", type="string", example="Password reset email sent."),
     *    )
     *     ),
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
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="The email must be a valid email address."
     *                 ),
     *         ))
     *        )
     *    ),
     * )
     */
    public function sendPasswordResetLink(ForgotPasswordRequest $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    protected function sendResetLinkResponse(ForgotPasswordRequest $request, $response): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => 'Мы отправили вам письмо на ' . $request->email . ' с инструкциями как сбросить пароль'
        ]);
    }

    protected function sendResetLinkFailedResponse(ForgotPasswordRequest $request, $response): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'data' => ' Извините, адрес электронной почты, который вы ввели, недействителен.'
        ]);
    }
}
