<?php

namespace App\api\Http\Controllers\Auth;

use App\api\Services\LoginService;
use App\api\Http\Controllers\Controller;
use App\api\Http\Requests\RegistrationRequest;


use App\Services\UserLocationService;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class RegistrationController extends Controller
{

    /**
     * @OA\Post(
     *        path="/register",
     *        summary="Register user",
     *        tags={"Auth"},
     *        @OA\RequestBody(
     *            required=true,
     *            description="Pass user credentials",
     *            @OA\JsonContent(
     *                required={"account_type", "name", "zip_code", "email", "nickname", "password"},
     *                @OA\Property(property="account_type", type="string", maxLength=20, example="user"),
     *                @OA\Property(property="user_type", type="string", maxLength=20, example="Venue"),
     *                @OA\Property(
     *                    property="user_sub_type",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                        type="string", example={"Club"},
     *                    )
     *                ),
     *                @OA\Property(property="zip_code", type="string", example="12345"),
     *                @OA\Property(property="name", type="string", example="Test"),
     *                @OA\Property(property="email", type="email", example="Test"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *            ),
     *         ),
     *        @OA\Response(
     *            response=200,
     *            description="Success",
     *            @OA\JsonContent(
     *                @OA\Property(property="status", type="string", example="success"),
     *                @OA\Property(
     *                    property="user",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
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
     *                        @OA\Property(
     *                            property="location",
     *                            type="object",
     *                            collectionFormat="multi",
     *                            @OA\Property(property="lat", type="numeric", example=-95.4040032),
     *                            @OA\Property(property="lng", type="numeric", example=29.6201681),
     *                        )
     *                    )
     *                ),
     *                @OA\Property(property="token", type="string", example=""),
     *            )
     *        ),
     *        @OA\Response(
     *            response=422,
     *            description="Returns when data is not valid",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="The given data was invalid."),
     *                @OA\Property(
     *                    property="errors",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                        @OA\Property(
     *                            property="email",
     *                            type="string",
     *                            example="Sorry, a user with this email already exists"
     *                        ),
     *                    )
     *                )
     *            )
     *        ),
     *     )
     * @throws \App\api\Exceptions\LoginException
     */
    public function register(RegistrationRequest $request, LoginService $loginService): \Illuminate\Http\JsonResponse
    {

        $user = new User();
        $user->name="any";
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $accesToken = $loginService->login($request->email, $request->password);
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $accesToken
        ]);

    }
}
