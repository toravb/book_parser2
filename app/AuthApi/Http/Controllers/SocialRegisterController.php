<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialProvidersRequest;
use App\Http\Requests\SocialRegisterRequest;
use App\Services\UserLocationService;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialRegisterController extends Controller
{

    //Password length
    public $length = 8;
    //Password generation character set
    public $password = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    /**
     * @OA\Get(
     *    path="/register/{provider}",
     *    summary="Register user with social network",
     *    description="Redirect user to Google or Facebook for register",
     *    tags={"Auth"},
     *    @OA\Parameter(
     *        description="Social provider",
     *        in="path",
     *        name="provider",
     *        required=true,
     *        example="google",
     *    ),
     *    @OA\Response(
     *    response=200,
     *    description="Redirect user to Google or redirect user back with error message",
     *    ),
     * )
     */
    public function redirectToGoogle(SocialProvidersRequest $request)
    {

        config([
            "services.$request->provider.redirect" => url("/api/register/$request->provider/callback")
        ]);

        return Socialite::driver($request->provider)->stateless()->redirect();
    }

    /**
     * @OA\Get(
     *    path="/register/{provider}/callback",
     *    summary="Register user with social network",
     *    description="Redirect user from Google or Facebook to front app",
     *    tags={"Auth"},
     *    @OA\Parameter(
     *        description="Social provider",
     *        in="path",
     *        name="provider",
     *        required=true,
     *        example="google",
     *    ),
     *    @OA\Response(
     *    response=200,
     *    description="Redirect user to front app with parametr or with error messages",
     *    ),
     * )
     */
    public function handleGoogleCallback(SocialProvidersRequest $request)
    {


        try {
            config([
                "services.$request->provider.redirect" => url("/api/register/$request->provider/callback")
            ]);
            $column = $request->provider . '_id';

            $googleUser = Socialite::driver($request->provider)->stateless()->user();

            $token = $googleUser->token;
            $email = $googleUser->email;

            $finduser = User::where($column, $googleUser->id)
                ->when($email, function ($query) use ($email) {
                    return $query->orwhere('email', $email);
                })
                ->first();
            if ($finduser) {
                $finduser->$column = $googleUser->id;

                $finduser->save();
                return redirect(url(config('app.front_url')) .
                    '/regisration-confirm?message=You are already registred! Please, Login!&type=');
            } else {
                $user = new User();
                $user->account_type = 'user';
                $user->address = 'Not point';
                $user->nickname = stristr($googleUser->email, '@', true);

                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $password = substr(str_shuffle($this->password), 0, $this->length);
                $user->password = Hash::make($password);
                $user->$column = $googleUser->id;
                $user->save();
                Cache::put('socialToken' . $user->id, $token, 3600);
                return redirect(url(config('app.front_url')) . '/regisration-confirm?token=' .
                    $token . '&provider=' . $request->provider . '&sid='
                    . $user->id . '&name=' . $googleUser->name . '&email=' . $googleUser->email);
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect(url(config('app.front_url')) . '/regisration-confirm?error=something went wrong');
        }
    }

    /**
     * @OA\Put(
     *    path="/register/{provider}",
     *    summary="Register user with social network",
     *    description="Saving additional parameters of user and get access Token",
     *    tags={"Auth"},
     *    @OA\Parameter(
     *        description="Social provider",
     *        in="path",
     *        name="provider",
     *        required=true,
     *        example="google",
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user's credentials",
     *         @OA\JsonContent(
     *             required={"user_type", "zip_code", "name","token" },
     *             @OA\Property(property="account_type", type="string", example="organizer"),
     *             @OA\Property(property="user_type", type="string", example="Venue"),
     *             @OA\Property(
     *                 property="user_sub_type",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     type="string", example={"Club"},
     *                 )
     *             ),
     *             @OA\Property(property="zip_code", type="string", example="12345"),
     *             @OA\Property(property="desc_user_type", type="string", example="Bar"),
     *             @OA\Property(property="name", type="string", example="Test"),
     *             @OA\Property(property="email", type="email", example="test@gmail."),
     *             @OA\Property(property="token", type="string", example="fgnbsrftghawergfserg"),
     *             @OA\Property(property="id", type="string", example="1234567898"),
     *         ),
     *      ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(property="accessToken", type="string", example="bslfdvhbazlfvhbzflhvb"),
     *          @OA\Property(
     *              property="data",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                        @OA\Property(property="id", type="integer", example="24"),
     *                        @OA\Property(property="account_type", type="string", example="user"),
     *                        @OA\Property(property="user_type", type="string", example="user"),
     *                        @OA\Property(
     *                            property="user_sub_type",
     *                            type="array",
     *                            collectionFormat="multi",
     *                            @OA\Items(
     *                                type="string", example={"Club"},
     *                            )
     *                        ),
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
     *                        ),
     *                       @OA\Property(
     *                            property="location",
     *                            type="object",
     *                            collectionFormat="multi",
     *                            @OA\Property(property="lat", type="numeric", example=-95.4040032),
     *                            @OA\Property(property="lng", type="numeric", example=29.6201681),
     *                        )
     *                    )
     *      ) )
     *    )
     *     ),
     * @OA\Response(
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
     *                      property="zip_code",
     *                      type="string",
     *                      example="The zip_coder may not be greater than 5 characters."
     *                 ),
     *         ))
     *        )
     *    ),
     * @OA\Response(
     *        response=403,
     *        description="Returns when token is not valid",
     *        @OA\JsonContent(
     *            @OA\Property(property="status", type="string", example="error"),
     *            @OA\Property(
     *                property="message",
     *                type="string",
     *                example="You do not have permission to access this action"
     *            ),
     *        )
     *    )
     * )
     */
    public function registerConfirm(SocialRegisterRequest $request, UserLocationService $locationService)
    {

        if (Cache::has('socialToken' . $request->id) and Cache::get('socialToken' . $request->id) == $request->token) {
            Cache::forget('socialToken' . $request->id);

            DB::beginTransaction();
            $user = User::where('id', $request->id)->first();
            $user->zip_code = $request->zip_code;
            $user->account_type = $request->account_type;
            $user->user_type = $request->user_type;
            $user->user_sub_type = $request->user_sub_type;
            $user->name = $request->name;
            if ($request->email) {
                $user->email = $request->email;
            }
            $user->save();

            if ($request->location !== null) {
                $locationService->defaultLocation($user->id, $request->location, $request['zip_code']);
            }
            DB::commit();


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
