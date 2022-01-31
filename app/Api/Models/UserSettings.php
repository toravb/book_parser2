<?php

namespace App\Api\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
