<?php

namespace App\AuthApi\Http\Controllers;

use App\AuthApi\Models\IdSocialNetwork;
use App\Http\Controllers\Controller;
use App\AuthApi\Http\Requests\SocialProvidersRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialAuthController extends Controller
{

    /**
     * @OA\Get(
     *        path="/auth/{provider}",
     *        summary="Authorization user with social network",
     *        description="Redirect user to Google or Facebook for authorization",
     *        tags={"Auth"},
     *        @OA\Parameter(
     *            description="Social provider",
     *            in="path",
     *            name="provider",
     *            required=true,
     *            example="google",
     *        ),
     *        @OA\Response(
     *            response=200,
     *            description="Redirect user to Google or redirect user back with error message",
     *        ),
     *    )
     */
    public function redirectToGoogle(SocialProvidersRequest $request)
    {
        config([
            "services.$request->provider.redirect" => url("/api/auth/$request->provider/callback")
        ]);

        return Socialite::driver($request->provider)->stateless()->redirect();
    }

    /**
     * @OA\Get(
     *        path="/auth/{provider}/callback",
     *        summary="Authorization user with social network",
     *        description="Redirect user from Google or Facebook to front app",
     *        tags={"Auth"},
     *        @OA\Parameter(
     *            description="Social provider",
     *            in="path",
     *            name="provider",
     *            required=true,
     *            example="google",
     *        ),
     *        @OA\Response(
     *        response=200,
     *        description="Redirect user to front app with parametr or with error messages",
     *        ),
     *    )
     */
    public function handleGoogleCallback(SocialProvidersRequest $request)
    {
//        try {
            config([
                "services.$request->provider.redirect" => url("/api/auth/$request->provider/callback")
            ]);

            $column = $request->provider . '_id';
     $code = $request->code;
     $client_id = env('ODNOKLASSNIKI_CLIENT_ID');
        $client_public = env('ODNOKLASSNIKI_CLIENT_PUBLIC');
        $client_secret = env('ODNOKLASSNIKI_CLIENT_SECRET');
        $redirect = env('ODNOKLASSNIKI_REDIRECT_URI');
        $response = Http::get('https://api.ok.ru/oauth/token.do?code=' . $code .
        '&client_id='.$client_id.'&client_secret=' . $client_secret . '&redirect_uri=' . $redirect . '&grant_type=authorization_code');
        dd($response->json());
        $socialUser = Socialite::driver($request->provider)->stateless()->userFromToken($code);

//            $socialUser = Socialite::driver($request->provider)->stateless()->user();
            dd($socialUser);

            $socialIdUser = IdSocialNetwork::where($column, $socialUser->id)->first();

            $findUser = User::where('email', $socialUser->email)->first();

            if ($findUser !== null || $socialIdUser !== null) {

                if($findUser !== null) {
                    $userId = $findUser->id;
                } else {
                    $userId = $socialIdUser->user_id;
                }

                Cache::put('socialToken' . $userId, $socialUser->token, 60);
                return redirect(url(config('app.front_url')) . '/login?token=' .
                    $socialUser->token . '&id=' . $userId);
            } else {
                return redirect(url(config('app.front_url')) . '/login?message=You are not registred!');
            }
//        } catch (Exception $e) {
//            Log::error($e);
//            return redirect(url(config('app.front_url')) . '/login?error=Something went wrong');
//        }
    }


    /**
     * @OA\Post(
     *        path="/auth",
     *        summary="Final part of user authorization with social network",
     *        description="Auth user and get access Token",
     *        tags={"Auth"},
     *        @OA\RequestBody(
     *            required=true,
     *            description="Pass user's credentials",
     *            @OA\JsonContent(
     *                required={"token", "id" },
     *                @OA\Property(property="token", type="string", example="fgnbsrftghawergfserg"),
     *                @OA\Property(property="id", type="string", example="1234567898"),
     *            ),
     *        ),
     *        @OA\Response(
     *        response=200,
     *        description="Success",
     *            @OA\JsonContent(
     *                @OA\Property(property="status", type="string", example="success"),
     *                @OA\Property(property="accessToken", type="string", example="bslfdvhbazlfvhbzflhvb"),
     *                @OA\Property(
     *                    property="data",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                        @OA\Property(property="id", type="integer", example="24"),
     *                        @OA\Property(property="user_type", type="string", example="user"),
     *                        @OA\Property(property="name", type="string", example="Maxim"),
     *                        @OA\Property(property="desc_user_type", type="string", example="Bar"),
     *                        @OA\Property(property="address", type="string", example="New York"),
     *                        @OA\Property(property="email", type="string", example="test012@mail.su"),
     *                        @OA\Property(property="phone_number", type="string", example="null"),
     *                        @OA\Property(property="nickname", type="string", example="TestLogin012"),
     *                        @OA\Property(property="email_verified_at", type="string", example="null"),
     *                        @OA\Property(property="google_id", type="string", example="12346579"),
     *                        @OA\Property(property="facebook_id", type="string", example="12346579"),
     *                        @OA\Property(property="website", type="string", example="http://site.com"),
     *                        @OA\Property(property="photo", type="string", example="/storage/1.img"),
     *                        @OA\Property(property="zip_code", type="string", example="50053"),
     *                        @OA\Property(property="background", type="string", example="/storage/2.img"),
     *                        @OA\Property(
     *                            property="social_links",
     *                            type="array",
     *                            collectionFormat="multi",
     *                            @OA\Items(
     *                                type="string",
     *                                example={"tw":"/mypage","i":"/instagramm", "f":"/myPage"},
     *                            )
     *                       )
     *                    )
     *                )
     *            )
     *        ),
     *        @OA\Response(
     *            response=403,
     *            description="Returns when token is not valid",
     *            @OA\JsonContent(
     *                @OA\Property(property="status", type="string", example="error"),
     *                @OA\Property(
     *                    property="message",
     *                    type="string",
     *                    example="You do not have permission to access this action"
     *                ),
     *            )
     *        )
     *     )
     */
    public function authConfirm(Request $request)
    {

        if (Cache::has('socialToken' . $request->id) and Cache::get('socialToken' . $request->id) == $request->token) {
            Cache::forget('socialToken' . $request->id);
            $user = User::where('id', $request->id)->first();

            Auth::loginUsingId($request->id);
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            return response()->json([
                'status' => 'success',
                'data' => $user,
                'accessToken' => $accessToken
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to access this action',
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
