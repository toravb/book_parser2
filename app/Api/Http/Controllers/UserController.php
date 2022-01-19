<?php

namespace App\Api\Http\Controllers;

use App\Api\Services\ApiAnswerService;
use App\AuthApi\Http\Controllers\LoginController;
use App\AuthApi\Models\IdSocialNetwork;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller

{
    public function destroy()
    {

        try {
            DB::beginTransaction();
            $user = Auth::user();
            User::where('id', $user->id)
                ->update([
                    'name' => null,
                    'email' =>null,
                    'password' => null,
                    'surname' => null,
                    'avatar'=>null
                 ]);
             $loginController = new LoginController();




           $idSocialNetwors = new IdSocialNetwork();
            $idSocialNetwors->where('user_id', $user->id)->delete();
            $loginController->logout();
            DB::commit();
            return response()->json([
                'status' => 'success',]);}

        catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
