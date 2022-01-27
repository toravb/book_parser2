<?php

namespace App\Models;

use App\Api\Interfaces\Types;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Like extends Model
{

    public function likeable()
    {
        return $this->morphTo();
    }

    public function addlike(Request $request)
    {
        $user = Auth::user();
        $params = [
            'user_id' => $user->id,
            'likeable_id' => $request->id,
            'likeable_type' => get_class($request)];
        Like::firstOrCreate($params);
        return back();
    }


}
