<?php

namespace App\Api\Models;

use App\api\Http\Requests\NotificationSettingsRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserSettings extends Model
{
    use HasFactory;

    public function create(User $user,NotificationSettingsRequest $request)
    {
       UserSettings::where('user_id', $user->id)
            ->update([
                'likes' => $request->likes,
                'commented' => $request->commented,
                'commentedOthers' => $request->commentedOthers]);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
