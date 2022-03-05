<?php

namespace App\Api\Http\Controllers;

use App\AuthApi\Http\Requests\SocialProvidersRequest;
use App\AuthApi\Models\IdSocialNetwork;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialNetworksController extends Controller
{
    public function redirectToGoogle(SocialProvidersRequest $request)
    {
        $userId = Auth::id();
        $hash = base64_encode(Hash::make($userId));
        config([
            "services.$request->provider.redirect" => url("/api/auth/$request->provider/callback")
        ]);
        IdSocialNetwork::updateOrCreate(
            [
                'user_id' => $userId
            ],
            [
                'temp_token' => $hash
            ]);

        return Socialite::driver($request->provider)
            ->stateless()
            ->with(['temp' => $hash])
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
    public function handleGoogleCallback(SocialProvidersRequest $request, User $userModel, IdSocialNetwork $idSocialNetwork)
    {
        try {
            config([
                "services.$request->provider.redirect" => url("/api/auth/$request->provider/callback")
            ]);

            $column = $request->provider . '_id';

            $socialUser = Socialite::driver($request->provider)->stateless()->user();
            $email = $socialUser->getEmail();
            $socialId = (int)$socialUser->getId();
            $hash = $request->input('temp');

            $socialIdUser = $idSocialNetwork->where('temp_token', $hash)->first();
            $findUser = User::where('email', $email)->first();

            if($socialIdUser->user_id === $findUser->id) {
                $idSocialNetwork->updateOrCreateNetworks($column, $findUser->id, $socialId);
                return redirect(url(config('app.front_url')));
            } else {
                return redirect(url(config('app.front_url')) . '/login?error=Something went wrong');
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect(url(config('app.front_url')) . '/login?error=Something went wrong');
        }
    }
}
