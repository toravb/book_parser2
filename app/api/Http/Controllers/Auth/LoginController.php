<?php

namespace App\api\Http\Controllers\Auth;

use App\api\Services\LoginService;
use App\Http\Controllers\Controller;
use App\api\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{



    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Log in",
     *     tags={"Auth"},
     *    @OA\RequestBody(
     *        required=true,
     *        description="Pass user credentials",
     *        @OA\JsonContent(
     *            required={"email","password"},
     *            @OA\Property(property="email", type="email", example="test@gmail.com"),
     *            @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *           @OA\Property(property="front_id", type="string", example="PassWord12345"),
     *        ),
     *     ),
     *     @OA\Response(response="200", description="Log in",  @OA\JsonContent(
     *         @OA\Property(property="token", type="string", description="Return access token",
     *                                        example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWY1NTc3NDdmMDBkZmExZDMzY
     *                                        mYxYWYyMjBlOGNhM2E0YmMxYmY2Yjg1YjZiZDI2NGMzZGJjNDE4NGI2ZWI2NWZkNTU0OTBjNjFjNmQ1NTEiLCJpYXQiOjE2MDMxMTg3NTMsIm5iZiI6MTYwMzExODc1MywiZXhwIjoxNjM0
     *                                        NjU0NzUzLCJzdWIiOiI2MyIsInNjb3BlcyI6W119.IcBUC4r7mJYopx3DEzIDX8lX06P6pqTwp5nnsOpMbSIHU67iBzI9mK-81VjckW-4kvkj7QtQnyetwBja-gwZIEDfF0s55Zs2Vx_V-
     *                                        JpkRH4kDkSeeF4H_MxjDvzwRNMbVNuBigG5AfPP8126f12y7JSji4QiIdXUX5uAbrIXROM0DYnl36j-op0sNUspYdXvFkLnrOtYe6PIv0hSPFFiNZFIRaGoBCHhTYMaycSotbKBnEavWCtU49s
     *                                        JaLg36hk2bRzvVPcZBYboYJNizaALyBNpqm9c2pXNtTccQUNk_C2gMMzPMpk1lizNdRpnZRBSDxsdSKAhvDswDpiitmh0q2nvlbiBF6kfToYkhwD7B0pgLonjGaDu4KU42PxdnI3-uVsxLMxB_218DX
     *                                        vvriQG171MMUbjJcV6T2MKjq4DbshY5T9TKGUlqDZ_RQk9OVqf0C80Vv4im5ryIZq1_0plgaUAQNL34wckuamXXowDvA-bXEm_n-EioLuCnSDrnxdYGVRhvolBZZJ9C7DL5JIm9vj3M0QX2kHS96XHSRet
     *                                        KbYIzRT9Vr6ihIngcZZdCi1rNYmvSie3K3csvjZYBIcblLx0IUlqvt2IZn1DNkcEGy8ATUMd9k_Ltdi3uDNv_x-IDKUvvksaRT1sZExiJLKa6xhfDffeZCkB_gKCEIMOFUM"),
     *          @OA\Property(property="front_id", type="string", example="PassWord12345"),
     *     )),
     *     @OA\Response(response="422", description="Log error parameters",  @OA\JsonContent())
     * )

     */
//    public function logn() {
//        dd(1);
//    }
    public function login(LoginRequest $request,  LoginService $loginService): \Illuminate\Http\JsonResponse
    {

dd($request->email);

//        $accessToken = $loginService->login($request->email, $request->password);
        return response()->json([
            'status' => 'success',
            'token' => $accessToken
        ]);

    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout",
     *     tags={"Auth"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(response="200", description="Logout",  @OA\JsonContent()),
     *     @OA\Response(response="401", description="Logout not authorized",  @OA\JsonContent()),
     * )
     */
    public function logout()
    {
        $accessToken = auth()->user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }
}
