<?php

namespace App\Api\Models;

use App\api\Http\Requests\NotificationSettingsRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'likes',
        'commented',
        'commentedOthers'
    ];

    public function create(int $userId, bool $likes, bool $commented, bool $commentedOthers) {
        $this
            ->updateOrCreate(
                ['user_id' => $userId],
                [
                'likes' => $likes,
                'commented' =>$commented,
                'commentedOthers' => $commentedOthers]);

    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
