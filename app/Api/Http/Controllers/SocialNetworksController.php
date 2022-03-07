<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\SocialProviderBindingRequest;
use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Requests\SocialProvidersRequest;
use App\AuthApi\Models\IdSocialNetwork;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GenerateUniqueTokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialNetworksController extends Controller
{
    public function getTempToken()
    {
        $userId = Auth::id();
        $hash = GenerateUniqueTokenService::createTokenWithUserId($userId);
        IdSocialNetwork::updateOrCreate(
            [
                'user_id' => $userId
            ],
            [
                'temp_token' => $hash,
                'token_valid_until' => Carbon::now()->addHour()
            ]);
        return ApiAnswerService::successfulAnswerWithData($hash);
    }


    public function redirectToGoogle(SocialProviderBindingRequest $request)
    {
        $hash = $request->input('hash');
        config([
            "services.$request->provider.redirect" => url("/api/profile/auth/$request->provider/callback")
        ]);

        return Socialite::driver($request->provider)
            ->stateless()
            ->with(['state' => $hash])
            ->redirect();
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
    public function handleGoogleCallback(SocialProvidersRequest $request, IdSocialNetwork $idSocialNetwork)
    {
        try {
            config([
                "services.$request->provider.redirect" => url("/api/profile/auth/$request->provider/callback")
            ]);

            $column = $request->provider . '_id';
            $hash = $request->input('state');
            if(!$hash) {
                return redirect(url(config('app.front_url')) . '/login?error=You did not went from your account');
            }
            $socialUser = Socialite::driver($request->provider)->stateless()->user();

            $socialId = (int)$socialUser->getId();


            $socialIdUser = $idSocialNetwork
                ->where('temp_token', $hash)
                ->where('token_valid_until', '>', Carbon::now())
                ->first();
            if(!$socialIdUser) {
                return redirect(url(config('app.front_url')) . '/login?error=Time for binding account maximum 1 hour. Refresh the page and try again');
            }

            if ($socialIdUser) {
                $idSocialNetwork->updateAfterBinding($column, $socialIdUser->user_id, $socialId);
                return redirect(url(config('app.front_url')) . '?success=You bind social network to your account');
            } else {
                return redirect(url(config('app.front_url')) . '/login?error=You did not went from your account');
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect(url(config('app.front_url')) . '/login?error=Something went wrong');
        }
    }
}
